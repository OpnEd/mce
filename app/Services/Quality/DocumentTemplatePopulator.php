<?php

namespace App\Services\Quality;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Process;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class DocumentTemplatePopulator
{
    /**
     * Key del config donde están las plantillas.
     */
    protected string $configKey = 'document_templates.default_docs';

    /**
     * Popula los documentos por defecto para un team (tenant).
     *
     * @param Team $team
     * @param User|null $consultant
     * @param bool $force Si true, sobrescribe documentos existentes aunque ya se hayan actualizado.
     * @return void
     */
    public function populate(Team $team, ?User $consultant = null, bool $force = false): void
    {
        $templates = config($this->configKey, []);

        if (! is_array($templates) || empty($templates)) {
            Log::info("DocumentTemplatePopulator: no hay plantillas en config ({$this->configKey}).");
            return;
        }

        // Envolvemos en transacción para seguridad en escritura múltiple
        DB::transaction(function () use ($templates, $team, $consultant, $force) {
            foreach ($templates as $tpl) {
                // Validaciones mínimas
                $slug = $tpl['slug'] ?? null;
                if (! $slug) {
                    Log::warning('Plantilla sin slug - se omite', ['tpl' => $tpl]);
                    continue;
                }

                // Resolver process_id y document_category_id por código
                $processCode = $tpl['process_id'] ?? null;
                $categoryCode = $tpl['document_category_id'] ?? null;

                $processId = $processCode ? Process::where('code', $processCode)->value('id') : null;
                $categoryId = $categoryCode ? DocumentCategory::where('code', $categoryCode)->value('id') : null;

                if (! $processId || ! $categoryId) {
                    Log::warning('Plantilla: proceso o category no encontrados', [
                        'tpl_slug' => $slug,
                        'process_code' => $processCode,
                        'category_code' => $categoryCode,
                    ]);
                    continue;
                }

                // Obtener o crear instancia en memoria
                $document = Document::firstOrNew([
                    'team_id' => $team->id,
                    'slug' => $slug,
                ]);

                // Si el documento existe y ya fue actualizado (no es creación),
                // saltar salvo que force === true
                if ($document->exists && ! $force) {
                    // Nota: si por alguna razón updated_at es null, lo saltamos defensivamente
                    $updatedAt = $document->updated_at ? Carbon::parse($document->updated_at) : null;
                    $createdAt = $document->created_at ? Carbon::parse($document->created_at) : null;

                    if ($updatedAt && $createdAt && $updatedAt->gt($createdAt)) {
                        Log::info('Plantilla existente y actualizada previamente - se omite', [
                            'team_id' => $team->id,
                            'slug' => $slug,
                        ]);
                        continue;
                    }
                }

                // Preparar los campos para guardar (asegurando shape correcto)
                $data = [
                    'title' => $tpl['title'] ?? null,
                    'sequence' => $tpl['sequence'] ?? 0,
                    'process_id' => $processId,
                    'document_category_id' => $categoryId,
                    'objective' => $tpl['objective'] ?? null,
                    'scope' => $tpl['scope'] ?? null,
                    // Aseguramos que los campos que deben ser arrays lo sean
                    'references' => Arr::wrap($tpl['references'] ?? []),
                    'terms' => Arr::wrap($tpl['terms'] ?? []),
                    'responsibilities' => Arr::wrap($tpl['responsibilities'] ?? []),
                    'procedure' => Arr::wrap($tpl['procedure'] ?? []),
                    'records' => Arr::wrap($tpl['records'] ?? []),
                    'annexes' => Arr::wrap($tpl['annexes'] ?? []),
                    'data' => $tpl['data'] ?? [],
                    // prepared_by -> id del consultor si existe
                    'prepared_by' => $consultant?->id,
                    // reviewed_by y approved_by: sólo si vienen numéricos en la plantilla
                    'reviewed_by' => is_numeric($tpl['reviewed_by'] ?? null) ? (int) $tpl['reviewed_by'] : null,
                    'approved_by' => is_numeric($tpl['approved_by'] ?? null) ? (int) $tpl['approved_by'] : null,
                ];

                // Fill + save con manejo claro de timestamps (Eloquent lo gestiona)
                $document->fill($data);
                $document->team_id = $team->id; // garantizar asociación por si es new
                $document->slug = $slug;

                // evitamos sobrescribir created_at si ya existe (Eloquent lo mantendrá)
                $saved = $document->save();

                if ($saved) {
                    Log::info('Plantilla creada/actualizada', [
                        'team_id' => $team->id,
                        'slug' => $slug,
                        'document_id' => $document->id,
                        'forced' => $force,
                    ]);
                } else {
                    Log::warning('Fallo al guardar plantilla', ['slug' => $slug, 'team_id' => $team->id]);
                }
            }
        });
    }
}
