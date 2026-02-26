<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Api\ExternalOrder;
use App\Models\Api\ExternalOrderTeamCandidate;
use App\Notifications\NewExternalOrderNotification;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use App\Models\Team;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * NotifyNearbyTeamsJob
 *
 * Job asincrónico que identifica equipos cercanos a una orden externa y les envía notificaciones.
 *
 * Flujo principal:
 * 1. Carga la orden externa y valida sus coordenadas (con geocodificación si es necesario)
 * 2. Busca equipos activos dentro del radio de notificación especificado usando cálculo Haversine
 * 3. Intenta geocodificar equipos sin coordenadas desde tenant_settings
 * 4. Crea registros de candidatos en external_order_team_candidates
 * 5. Marca la orden como NOTIFIED
 * 6. Envía notificaciones a usuarios de equipos candidatos
 *
 * Características de resiliencia:
 * - Reintentos configurables (5 por defecto)
 * - Transacciones para consistencia de datos
 * - Geocodificación mediante Nominatim/OpenStreetMap
 * - Manejo de rate-limiting para APIs externas
 * - Logging detallado de cada paso
 * - Idempotencia mediante firstOrCreate en candidatos
 *
 * @package App\Jobs
 * @author Desarrollo
 * @implements ShouldQueue
 */
class NotifyNearbyTeamsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * ID de la orden externa a procesar
     *
     * @var int
     */
    public int $externalOrderId;

    /**
     * Número máximo de reintentos del job
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Radio de notificación en metros (500 km por defecto)
     *
     * @var int
     */
    protected int $notifyRadiusMeters = 500000;

    /**
     * URL base de API Nominatim para geocodificación
     *
     * @var string
     */
    protected string $nominatimUrl;

    /**
     * User-Agent para cumplir con políticas de Nominatim
     *
     * @var string
     */
    protected string $userAgent;

    /**
     * Email de contacto para Nominatim (requerido por su política de uso)
     *
     * @var string|null
     */
    protected ?string $email = null;

    /**
     * Constructor del Job
     *
     * Inicializa las propiedades del job con configuración de Nominatim.
     * Construye el User-Agent según políticas de Nominatim.
     *
     * @param int $externalOrderId ID de la orden externa a procesar
     */
    public function __construct(int $externalOrderId)
    {
        $this->externalOrderId = $externalOrderId;
        $this->nominatimUrl = config('services.nominatim.url', 'https://nominatim.openstreetmap.org');
        
        $this->email = config('services.nominatim.email');
        $appName = config('app.name', 'Droguería Digital');
        
        // Construir User-Agent con Email si no está definido explícitamente
        $defaultUA = $appName . '/1.0';
        if ($this->email) {
            $defaultUA .= " ({$this->email})";
        }
        
        $this->userAgent = config('services.nominatim.user_agent', $defaultUA);
    }

    /**
     * Procesa el job: identifica equipos cercanos y envía notificaciones
     *
     * Flujo de ejecución:
     * 1. Valida configuración de Nominatim
     * 2. Carga la orden externa
     * 3. Verifica/geocodifica coordenadas del cliente
     * 4. Busca equipos dentro del radio de notificación
     * 5. Crea candidatos de entrega
     * 6. Actualiza estado de orden a NOTIFIED
     * 7. Envía notificaciones a usuarios de equipos candidatos
     *
     * @return void
     * @throws \Exception Si falta configuración de Nominatim o error crítico
     */
    public function handle(): void
    {
        // Validación: Email obligatorio para cumplir políticas de Nominatim
        if (empty($this->email)) {
            $msg = 'NotifyNearbyTeamsJob: El email es obligatorio para cumplir políticas de Nominatim (services.nominatim.email).';
            Log::critical($msg);
            throw new \Exception($msg);
        }

        // Paso 1: Cargar la orden externa
        $order = ExternalOrder::with('items')->find($this->externalOrderId);

        if (! $order) {
            Log::warning('NotifyNearbyTeamsJob: ExternalOrder not found', ['id' => $this->externalOrderId]);
            return;
        }

        // Usar radio dinámico de la orden si existe, o el default de la clase
        $radiusMeters = $order->notify_radius_m ?? $this->notifyRadiusMeters;

        // Si la orden ya fue asignada a un equipo, no procesar
        if ($order->team_id !== null) {
            Log::info('NotifyNearbyTeamsJob: order already assigned, skipping', ['order_id' => $order->id, 'team_id' => $order->team_id]);
            return;
        }

        // Paso 2: Validar/geocodificar coordenadas del cliente
        // Si existen coordenadas, usarlas; si no, intentar geocodificación mediante Nominatim
        if (! empty($order->customer_lat) && ! empty($order->customer_lng)) {
            Log::info('NotifyNearbyTeamsJob: Using order stored coordinates', ['order_id' => $order->id, 'lat' => $order->customer_lat, 'lng' => $order->customer_lng]);
        } else {
            $addressString = $order->customer_address; // free-form address
            if (! empty($addressString)) {
                try {
                    $coords = $this->geocodeFreeformAddress($addressString);
                    if ($coords) {
                        $order->customer_lat = $coords['lat'];
                        $order->customer_lng = $coords['lng'];
                        $order->saveQuietly();
                        Log::info('NotifyNearbyTeamsJob: geocoded order address', ['order_id' => $order->id, 'lat' => $coords['lat'], 'lng' => $coords['lng']]);
                    } else {
                        Log::warning('NotifyNearbyTeamsJob: geocoding returned no result for order address', ['order_id' => $order->id, 'address' => $addressString]);
                    }
                } catch (Throwable $e) {
                    Log::error('NotifyNearbyTeamsJob: error geocoding order address', ['order_id' => $order->id, 'exception' => $e->getMessage()]);
                }
            } else {
                Log::warning('NotifyNearbyTeamsJob: order has no customer address to geocode', ['order_id' => $order->id]);
            }
        }

        // Validar que las coordenadas estén disponibles
        if (empty($order->customer_lat) || empty($order->customer_lng)) {
            Log::warning('NotifyNearbyTeamsJob: cannot proceed, order missing lat/lng', ['order_id' => $order->id]);
            return;
        }

        $customerLat = (float) $order->customer_lat;
        $customerLng = (float) $order->customer_lng;

        // Paso 3: Buscar equipos activos dentro del radio de notificación
        // Primero buscamos equipos que ya tienen coordenadas guardadas
        // We'll compute distances via SQL (Haversine) for teams with lat/lng.
        $earthRadius = 6371000; // meters

        // Expresión Haversine en SQL para calcular distancia en metros
        // Fórmula: distancia = R * arccos(sin(lat1)*sin(lat2) + cos(lat1)*cos(lat2)*cos(lng2-lng1))
        $haversine = "( $earthRadius * acos( cos( radians(?) ) * cos( radians(latitude) ) * cos( radians(longitude) - radians(?) ) + sin( radians(?) ) * sin( radians(latitude) ) ) )";

        // Buscar equipos que ya tienen coordenadas
        $teamsWithCoords = DB::table('teams')
            ->selectRaw("teams.*, $haversine as distance_m", [$customerLat, $customerLng, $customerLat])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('is_active', true)
            ->having('distance_m', '<=', $radiusMeters)
            ->orderBy('distance_m', 'asc')
            ->get();

        $candidates = [];

        foreach ($teamsWithCoords as $teamRow) {
            $candidates[] = [
                'team_id' => $teamRow->id,
                'distance_m' => (float) $teamRow->distance_m,
                'lat' => (float) $teamRow->latitude,
                'lng' => (float) $teamRow->longitude,
            ];
        }

        // Paso 4: Geocodificar equipos sin coordenadas
        // Obtener dirección desde tenant_settings e intentar geocodificar
        // We'll fetch a manageable number to geocode to avoid hitting rate limits; you may adjust or paginate
        $teamsWithoutCoords = DB::table('teams')
            ->where(function ($q) {
                $q->whereNull('latitude')->orWhereNull('longitude');
            })
            ->where('is_active', true)
            ->limit(5) // Límite de seguridad para evitar Timeouts por el sleep(1)
            ->get();

        foreach ($teamsWithoutCoords as $teamRow) {
            try {
                // Try to get address data from tenant_settings (joined with settings where group = 'address')
                $addressRecord = DB::table('tenant_settings')
                    ->join('settings', 'tenant_settings.setting_id', '=', 'settings.id')
                    ->where('tenant_settings.team_id', $teamRow->id)
                    ->where('settings.group', 'address')
                    ->select('tenant_settings.data', 'tenant_settings.value')
                    ->first();

                $structuredAddress = null;

                if ($addressRecord) {
                    // Attempt to parse JSON 'data' first (recommended)
                    if (! empty($addressRecord->data)) {
                        $decoded = json_decode($addressRecord->data, true);
                        if (is_array($decoded)) {
                            $structuredAddress = $decoded;
                        }
                    }

                    // If data empty, try to use 'value' as freeform string
                    if (! $structuredAddress && ! empty($addressRecord->value)) {
                        $structuredAddress = ['q' => $addressRecord->value];
                    }
                }

                if (! $structuredAddress) {
                    Log::debug('NotifyNearbyTeamsJob: team has no address data in tenant_settings', ['team_id' => $teamRow->id]);
                    continue;
                }

                // Geocode using structured search if we have components, otherwise freeform
                $coords = null;
                if (isset($structuredAddress['street']) || isset($structuredAddress['city']) || isset($structuredAddress['country'])) {
                    $coords = $this->geocodeStructuredAddress($structuredAddress);
                } elseif (isset($structuredAddress['q'])) {
                    $coords = $this->geocodeFreeformAddress($structuredAddress['q']);
                }

                if ($coords) {
                    // Save back to teams table to cache coordinates (minimize future geocoding)
                    DB::table('teams')->where('id', $teamRow->id)->update([
                        'latitude' => $coords['lat'],
                        'longitude' => $coords['lng'],
                        'updated_at' => now(),
                    ]);

                    // compute distance in meters using PHP haversine
                    $distanceMeters = $this->haversineDistanceMeters($customerLat, $customerLng, $coords['lat'], $coords['lng']);

                    if ($distanceMeters <= $radiusMeters) {
                        $candidates[] = [
                            'team_id' => $teamRow->id,
                            'distance_m' => $distanceMeters,
                            'lat' => $coords['lat'],
                            'lng' => $coords['lng'],
                        ];
                    }
                } else {
                    Log::warning('NotifyNearbyTeamsJob: geocoding failed for team', ['team_id' => $teamRow->id, 'address' => $structuredAddress]);
                }
            } catch (RequestException $e) {
                Log::error('NotifyNearbyTeamsJob: HTTP error during geocoding for team', ['team_id' => $teamRow->id, 'message' => $e->getMessage()]);
                // continue with others
            } catch (Throwable $e) {
                Log::error('NotifyNearbyTeamsJob: unexpected error while geocoding team', ['team_id' => $teamRow->id, 'message' => $e->getMessage()]);
            }
        }

        // Eliminar duplicados (en caso que el mismo equipo se encuentre dos veces)
        $candidates = collect($candidates)
            ->unique('team_id')
            ->sortBy('distance_m')
            ->values()
            ->all();

        // Paso 5: Crear registros de candidatos y actualizar estado de orden a NOTIFIED
        $newNotifications = DB::transaction(function () use ($order, $candidates) {
            // Bloquear la orden para evitar que otros procesos la modifiquen simultáneamente
            $lockedOrder = ExternalOrder::lockForUpdate()->find($order->id);

            // Si la orden fue asignada mientras geocodificábamos, abortar
            if (! $lockedOrder || $lockedOrder->team_id !== null) {
                return [];
            }

            $notificationsToSend = [];

            // Procesar cada equipo candidato
            foreach ($candidates as $cand) {
                // Crear candidato con idempotencia: si ya existe, no duplicar
                $candidate = ExternalOrderTeamCandidate::firstOrCreate(
                    [
                        'external_order_id' => $lockedOrder->id,
                        'team_id' => $cand['team_id'],
                    ],
                    [
                        'distance_km' => round($cand['distance_m'] / 1000, 3),
                        'distance_m' => (int) round($cand['distance_m']),
                        'status' => 'notified',
                        'notified_at' => now(),
                    ]
                );

                // Solo notificar si es un registro nuevo (evita duplicados en reintentos)
                if ($candidate->wasRecentlyCreated) {
                    $notificationsToSend[] = [
                        'team_id' => $cand['team_id'],
                        'distance_m' => $cand['distance_m']
                    ];
                }
            }

            // ACTUALIZAR ESTADO DE LA ORDEN A NOTIFIED
            // Si hay candidatos, marcar como NOTIFIED; si no hay candidatos, marcar como NO_CANDIDATES
            $status = empty($candidates) ? 'NO_CANDIDATES' : 'NOTIFIED';
            $lockedOrder->update(['status' => $status]);
            
            Log::info('NotifyNearbyTeamsJob: ExternalOrder status updated', [
                'order_id' => $lockedOrder->id,
                'status' => $status,
                'candidates_count' => count($candidates)
            ]);

            return $notificationsToSend;
        });

        // Paso 6: Enviar notificaciones (fuera de la transacción para mayor resiliencia)
        Log::info('NotifyNearbyTeamsJob: sending notifications to teams', [
            'order_id' => $order->id,
            'teams'    => count($newNotifications),
        ]);

        $dispatchedCount = 0;

        // Iterar sobre cada equipo candidato y notificar a sus usuarios
        foreach ($newNotifications as $item) {
            $team = Team::find($item['team_id']);
            if (! $team) {
                continue;
            }

            // Cargar usuarios del equipo
            $team->loadMissing('users');

            // Notificar a cada usuario activo del equipo
            foreach ($team->users as $user) {
                // Saltar usuarios suspendidos
                if ($user->is_suspended) {
                    continue;
                }

                // Evitar duplicar notificaciones si el usuario ya fue notificado
                $alreadyNotified = $user->notifications()
                    ->where('data->order_id', $order->id)
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                try {
                    // Enviar notificación al usuario
                    $user->notify(new NewExternalOrderNotification($order, $team, (int) round($item['distance_m'])));

                    // Disparar evento para refrescar notificaciones en Filament (Livewire/Echo)
                    try {
                        DatabaseNotificationsSent::dispatch($user);
                    } catch (\Throwable $e) {
                        Log::warning('NotifyNearbyTeamsJob: no se pudo enviar evento de broadcast (¿Reverb/Pusher no configurados?)', ['error' => $e->getMessage()]);
                    }
                    
                    $dispatchedCount++;

                } catch (\Throwable $e) {
                    Log::error('NotifyNearbyTeamsJob: notification send failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                }
            }
        }

        // Log final: resumen de notificaciones enviadas
        Log::info('NotifyNearbyTeamsJob: completed successfully', [
            'order_id'              => $order->id,
            'total_notifications'   => $dispatchedCount,
            'candidates_count'      => count($newNotifications),
        ]);
    }

    /**
     * Geocodifica una dirección en formato libre usando Nominatim/OpenStreetMap
     *
     * @param string $address Dirección en formato libre (ej: "Calle Principal 123, Ciudad")
     * @return array|null Array con estructura ['lat' => float, 'lng' => float] o null si falla
     * @throws \Exception Si la API retorna 403 Forbidden (rate limit o User-Agent inválido)
     */
    protected function geocodeFreeformAddress(string $address): ?array
    {
        // Aplicar rate-limit estricto: 1 segundo antes de cada petición (política de Nominatim)
        sleep(1);

        $endpoint = rtrim($this->nominatimUrl, '/') . '/search';
        $params = [
            'format' => 'json',
            'q' => $address,
            'limit' => 1,
            'addressdetails' => 0,
        ];

        // Realizar petición HTTP con headers requeridos por Nominatim
        $response = Http::withOptions([
            'verify' => false,
            'timeout' => 10,
        ])->withHeaders([
            'User-Agent' => $this->userAgent,
            'Accept-Language' => 'es',
        ])->get($endpoint, $params);

        // Validar respuesta: 403 Forbidden indica problema de User-Agent o rate limit
        if ($response->status() === 403) {
            Log::critical('NotifyNearbyTeamsJob: Nominatim 403 Forbidden. Verificar User-Agent y Rate Limits', ['ua' => $this->userAgent]);
            throw new \Exception('Nominatim 403 Forbidden');
        }

        if (! $response->ok()) {
            Log::warning('NotifyNearbyTeamsJob: Nominatim freeform request failed', ['status' => $response->status(), 'address' => $address]);
            return null;
        }

        $json = $response->json();

        // Validar que la respuesta contenga al menos un resultado con lat/lng
        if (empty($json) || ! isset($json[0]['lat'], $json[0]['lon'])) {
            return null;
        }

        return [
            'lat' => (float) $json[0]['lat'],
            'lng' => (float) $json[0]['lon'],
        ];
    }

    /**
     * Geocodifica una dirección estructurada usando parámetros de Nominatim
     *
     * Soporta componentes: street, city, state, country, postalcode
     *
     * @param array $structured Array con claves como 'street', 'city', 'state', 'country', 'postalcode', etc.
     * @return array|null Array con estructura ['lat' => float, 'lng' => float] o null si falla
     * @throws \Exception Si la API retorna 403 Forbidden
     */
    protected function geocodeStructuredAddress(array $structured): ?array
    {
        // Aplicar rate-limit estricto: 1 segundo antes de cada petición
        sleep(1);

        $endpoint = rtrim($this->nominatimUrl, '/') . '/search';
        $params = [
            'format' => 'json',
            'limit' => 1,
            'addressdetails' => 0,
        ];

        // Mapear componentes de dirección estructurada
        if (! empty($structured['street'])) $params['street'] = $structured['street'];
        if (! empty($structured['city'])) $params['city'] = $structured['city'];
        if (! empty($structured['state'])) $params['state'] = $structured['state'];
        if (! empty($structured['country'])) $params['country'] = $structured['country'];
        if (! empty($structured['postalcode'])) $params['postalcode'] = $structured['postalcode'];

        // Si existe búsqueda libre (q), incluirla también
        if (! empty($structured['q'])) $params['q'] = $structured['q'];

        // Realizar petición HTTP con headers requeridos
        $response = Http::withHeaders([
            'User-Agent' => $this->userAgent,
            'Accept-Language' => 'es',
        ])->get($endpoint, $params);

        // Validar respuesta: 403 Forbidden
        if ($response->status() === 403) {
            Log::critical('NotifyNearbyTeamsJob: Nominatim 403 Forbidden. Verificar User-Agent y Rate Limits', ['ua' => $this->userAgent]);
            throw new \Exception('Nominatim 403 Forbidden');
        }

        if (! $response->ok()) {
            Log::warning('NotifyNearbyTeamsJob: Nominatim structured request failed', ['status' => $response->status(), 'params' => $params]);
            return null;
        }

        $json = $response->json();
        if (empty($json) || ! isset($json[0]['lat'], $json[0]['lon'])) {
            return null;
        }

        return [
            'lat' => (float) $json[0]['lat'],
            'lng' => (float) $json[0]['lon'],
        ];
    }

    /**
     * Calcula la distancia Haversine entre dos puntos geográficos
     *
     * Utiliza la fórmula de Haversine para calcular la distancia en metros
     * entre dos coordenadas lat/lng sobre la superficie terrestre.
     *
     * @param float $latFrom Latitud del punto de origen
     * @param float $lngFrom Longitud del punto de origen
     * @param float $latTo Latitud del punto de destino
     * @param float $lngTo Longitud del punto de destino
     * @return float Distancia en metros
     */
    protected function haversineDistanceMeters(float $latFrom, float $lngFrom, float $latTo, float $lngTo): float
    {
        $earthRadius = 6371000; // meters

        $latFromRad = deg2rad($latFrom);
        $latToRad = deg2rad($latTo);
        $deltaLat = deg2rad($latTo - $latFrom);
        $deltaLng = deg2rad($lngTo - $lngFrom);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($latFromRad) * cos($latToRad) *
            sin($deltaLng / 2) * sin($deltaLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
