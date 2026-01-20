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

class NotifyNearbyTeamsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $externalOrderId;

    // Retry attempts
    public $tries = 5;

    // Radius in meters (5 km)
    protected int $notifyRadiusMeters = 500000;

    // Nominatim base URL (configurable)
    protected string $nominatimUrl;

    // User-Agent for Nominatim requirement (config/services.php or fallback)
    protected string $userAgent;

    protected ?string $email = null;

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

    public function handle(): void
    {
        // Política: Email obligatorio
        if (empty($this->email)) {
            $msg = 'NotifyNearbyTeamsJob: El email es obligatorio para cumplir políticas de Nominatim (services.nominatim.email).';
            Log::critical($msg);
            throw new \Exception($msg);
        }

        // 1) Load the external order fresh
        $order = ExternalOrder::with('items')->find($this->externalOrderId);

        if (! $order) {
            Log::warning('NotifyNearbyTeamsJob: ExternalOrder not found', ['id' => $this->externalOrderId]);
            return;
        }

        // FUSION: Usar radio dinámico de la orden si existe, o el default de la clase
        $radiusMeters = $order->notify_radius_m ?? $this->notifyRadiusMeters;

        // If already assigned, nothing to do
        if ($order->team_id !== null) {
            Log::info('NotifyNearbyTeamsJob: order already assigned, skipping', ['order_id' => $order->id, 'team_id' => $order->team_id]);
            return;
        }

        // Idempotencia: Si ya fue notificada o cancelada, no procesar de nuevo.
        /* if (in_array($order->status, ['notified', 'cancelled', 'no_candidates'])) {
            Log::info('NotifyNearbyTeamsJob: order status is final or processed, skipping', ['order_id' => $order->id, 'status' => $order->status]);
            return;
        } */

        // 2) Ensure we have customer's lat/lng. If present, use them; otherwise attempt geocoding using Nominatim.
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

        // Re-check customer coords
        if (empty($order->customer_lat) || empty($order->customer_lng)) {
            Log::warning('NotifyNearbyTeamsJob: cannot proceed, order missing lat/lng', ['order_id' => $order->id]);
            return;
        }

        $customerLat = (float) $order->customer_lat;
        $customerLng = (float) $order->customer_lng;

        // 3) Query teams that already have coordinates and are active
        // We'll compute distances via SQL (Haversine) for teams with lat/lng.
        $earthRadius = 6371000; // meters

        // Haversine SQL expression (distance in meters)
        // Se eliminan los prefijos 'teams.' para mayor seguridad y compatibilidad
        $haversine = "( $earthRadius * acos( cos( radians(?) ) * cos( radians(latitude) ) * cos( radians(longitude) - radians(?) ) + sin( radians(?) ) * sin( radians(latitude) ) ) )";

        // Select teams with coordinates first
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

        // 4) For teams without coordinates, attempt to geocode them from tenant_settings -> address
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

        // Remove duplicates (in case same team found twice)
        $candidates = collect($candidates)
            ->unique('team_id')
            ->sortBy('distance_m')
            ->values()
            ->all();

        // 5) Create external_order_team_candidates entries (Transactional) & Identify new notifications
        $newNotifications = DB::transaction(function () use ($order, $candidates) {
            // Bloqueamos la orden para asegurar que nadie más la esté modificando en este instante
            $lockedOrder = ExternalOrder::lockForUpdate()->find($order->id);

            // Si la orden fue tomada o eliminada mientras geocodificábamos, abortamos
            if (! $lockedOrder || $lockedOrder->team_id !== null) {
                return [];
            }

            $notificationsToSend = [];

            foreach ($candidates as $cand) {
                // Usamos firstOrCreate para manejar la idempotencia de forma segura
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

                // Solo notificamos si el registro se acaba de crear (evita duplicados en reintentos)
                if ($candidate->wasRecentlyCreated) {
                    $notificationsToSend[] = [
                        'team_id' => $cand['team_id'],
                        'distance_m' => $cand['distance_m']
                    ];
                }
            }

            // Actualizar estado para evitar reprocesos futuros
            $status = empty($candidates) ? 'no_candidates' : 'notified';
            $lockedOrder->update(['status' => $status]);

            return $notificationsToSend;
        });

        // 6) Send notifications outside transaction (Fail-safe)
        Log::info('NotifyNearbyTeamsJob: notifying users', [
            'order_id' => $order->id,
            'teams'    => count($newNotifications),
        ]);

        $dispatchedCount = 0;

        foreach ($newNotifications as $item) {
            $team = Team::find($item['team_id']);
            if (! $team) {
                continue;
            }

            // Cargar users del team
            $team->loadMissing('users');

            foreach ($team->users as $user) {
                if ($user->is_suspended) {
                    continue;
                }

                // Evitar notificación duplicada
                $alreadyNotified = $user->notifications()
                    ->where('data->order_id', $order->id)
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                try {

                    $user->notify(new NewExternalOrderNotification($order, $team, (int) round($item['distance_m'])));

                    // 2) Trigger immediate refresh of Filament database notifications (Livewire/Echo)
                    try {
                        DatabaseNotificationsSent::dispatch($user);
                    } catch (\Throwable $e) {
                        Log::warning('NotifyNearbyTeamsJob: No se pudo enviar evento de broadcast (¿Falta configurar Reverb/Pusher?)', ['error' => $e->getMessage()]);
                    }
                    
                    $dispatchedCount++;

                } catch (\Throwable $e) {
                    Log::error('NotifyNearbyTeamsJob: notification failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                }
            }
        }

        Log::info('NotifyNearbyTeamsJob: notifications dispatched', [
            'order_id'          => $order->id,
            'new_notifications' => $dispatchedCount,
        ]);
    }

    /**
     * Geocode freeform address using Nominatim / OpenStreetMap.
     * Returns ['lat' => float, 'lng' => float] or null.
     */
    protected function geocodeFreeformAddress(string $address): ?array
    {
        // Rate-limit estricto (1s antes de la petición)
        sleep(1);

        $endpoint = rtrim($this->nominatimUrl, '/') . '/search';
        $params = [
            'format' => 'json',
            'q' => $address,
            'limit' => 1,
            'addressdetails' => 0,
        ];

        $response = Http::withOptions([
            'verify' => false,
            'timeout' => 10,
        ])->withHeaders([
            'User-Agent' => $this->userAgent,
            'Accept-Language' => 'es',
        ])->get($endpoint, $params);

        // Manejo explícito de 403
        if ($response->status() === 403) {
            Log::critical('NotifyNearbyTeamsJob: Nominatim 403 Forbidden. Verifique User-Agent y Rate Limits.', ['ua' => $this->userAgent]);
            throw new \Exception('Nominatim 403 Forbidden');
        }

        if (! $response->ok()) {
            Log::warning('NotifyNearbyTeamsJob: nominatim freeform request not ok', ['status' => $response->status(), 'address' => $address]);
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
     * Geocode structured address using Nominatim parameters (street, city, state, country, postalcode).
     * $structured is an array with keys like 'street','city','state','country','postalcode' or similar.
     */
    protected function geocodeStructuredAddress(array $structured): ?array
    {
        // Rate-limit estricto (1s antes de la petición)
        sleep(1);

        $endpoint = rtrim($this->nominatimUrl, '/') . '/search';
        $params = [
            'format' => 'json',
            'limit' => 1,
            'addressdetails' => 0,
        ];

        // Map known keys
        if (! empty($structured['street'])) $params['street'] = $structured['street'];
        if (! empty($structured['city'])) $params['city'] = $structured['city'];
        if (! empty($structured['state'])) $params['state'] = $structured['state'];
        if (! empty($structured['country'])) $params['country'] = $structured['country'];
        if (! empty($structured['postalcode'])) $params['postalcode'] = $structured['postalcode'];

        // If there is a freeform q, include it as well
        if (! empty($structured['q'])) $params['q'] = $structured['q'];

        $response = Http::withHeaders([
            'User-Agent' => $this->userAgent,
            'Accept-Language' => 'es',
        ])->get($endpoint, $params);

        // Manejo explícito de 403
        if ($response->status() === 403) {
            Log::critical('NotifyNearbyTeamsJob: Nominatim 403 Forbidden. Verifique User-Agent y Rate Limits.', ['ua' => $this->userAgent]);
            throw new \Exception('Nominatim 403 Forbidden');
        }

        if (! $response->ok()) {
            Log::warning('NotifyNearbyTeamsJob: nominatim structured request not ok', ['status' => $response->status(), 'params' => $params]);
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
     * Haversine distance in meters between two lat/lng points.
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
