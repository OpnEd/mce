<?php

namespace App\Livewire\Quality;

use Livewire\Component;
use App\Models\MinutesIvcSection;
use App\Models\MinutesIvcSectionEntry;

class PopulateMinutesIvc extends Component
{
    public function populateSections($teamId)
    {
        // 1. Poblar minutes_ivc_sections
        $sections = config('minutes-ivc-sections');
        foreach ($sections as $s) {
            MinutesIvcSection::updateOrCreate(
                [
                    'team_id' => $teamId, 
                    'order' => $s['order'] ?? null, 
                    'slug' => $s['slug'] ?? null,
                    'name' => $s['name'] ?? null, 
                    'description' => $s['description'] ?? null, 
                    'status' => $s['status'] ?? null
                ]
            );
        }

        // 2. Obtener IDs de las secciones
        $sectionNames = [
            'Talento Humano'                    => 'minutes_ivc_section_rec_hum_id',
            'Infraestructura Física'            => 'minutes_ivc_section_infr_fis_id',
            'Saneamiento de edificaciones'      => 'minutes_ivc_section_san_edif_id',
            'Áreas'                             => 'minutes_ivc_section_areas_id',
            'Sistema de gestión de calidad'     => 'minutes_ivc_section_gest_cal_id',
            'Procesos y procedimientos'         => 'minutes_ivc_section_proc_proce_id',
            'Revisión de productos'             => 'minutes_ivc_section_rev_prod_id',
            'Revisión de Otros aspectos'        => 'minutes_ivc_section_rev_otros_asp_id',
        ];
        $sectionIds = [];
        foreach ($sectionNames as $sectionName => $varName) {
            $sectionIds[$varName] = MinutesIvcSection::where('team_id', $teamId)
                ->where('name', $sectionName)
                ->first()
                ?->id;
        }

        // 3. Poblar entries de cada sección
        $sectionConfigMap = [
            'minutes_ivc_section_rec_hum_id'        => 'minutes-ivc-second-section-entries',
            'minutes_ivc_section_infr_fis_id'       => 'minutes-ivc-third-section-entries',
            'minutes_ivc_section_san_edif_id'       => 'minutes-ivc-fourth-section-entries',
            'minutes_ivc_section_areas_id'          => 'minutes-ivc-fifth-section-entries',
            'minutes_ivc_section_gest_cal_id'       => 'minutes-ivc-sixth-section-entries',
            'minutes_ivc_section_proc_proce_id'     => 'minutes-ivc-seventh-section-entries',
            'minutes_ivc_section_rev_prod_id'       => 'minutes-ivc-eighth-section-entries',
            'minutes_ivc_section_rev_otros_asp_id'  => 'minutes-ivc-ninth-section-entries',
        ];
        foreach ($sectionConfigMap as $sectionVar => $configName) {
            $sectionId = $sectionIds[$sectionVar] ?? null;
            $rawEntries = config($configName, []);
            $entries = $this->flattenMinutesIvcEntries(is_array($rawEntries) ? $rawEntries : []);

            if ($sectionId && !empty($entries)) {
                foreach ($entries as $e) {
                    if (empty($e['entry_id'])) {
                        continue;
                    }

                    MinutesIvcSectionEntry::updateOrCreate(
                        [
                            'minutes_ivc_section_id' => $sectionId,
                            'entry_id' => $e['entry_id'],
                        ],
                        [
                            'question' => $e['question'] ?? null,
                            'apply' => $e['apply'] ?? true,
                            'criticality' => $e['criticality'] ?? null,
                            'answer' => $e['answer'] ?? null,
                            'entry_type' => $e['entry_type'] ?? null,
                            'links' => $e['links'] ?? null,
                            'compliance' => $e['compliance'] ?? null,
                        ]
                    );
                }
            }
        }
    }

    private function flattenMinutesIvcEntries(array $node): array
    {
        $flat = [];

        foreach ($node as $item) {
            if (!is_array($item)) {
                continue;
            }

            if ($this->isMinutesIvcLeafEntry($item)) {
                $flat[] = $item;
                continue;
            }

            $flat = array_merge($flat, $this->flattenMinutesIvcEntries($item));
        }

        return $flat;
    }

    private function isMinutesIvcLeafEntry(array $item): bool
    {
        return array_key_exists('entry_id', $item) && array_key_exists('question', $item);
    }

    public function render()
    {
        return view('livewire.quality.populate-minutes-ivc');
    }
}
