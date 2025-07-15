<?php

namespace App\Services;

use App\Models\MinutesIvcSection;
use App\Models\MinutesIvcSectionEntry;

class MinutesIvcService
{
    /**
     * Obtiene todas las secciones de un equipo ordenadas.
     */
    public function getSectionsByTeam(int $teamId)
    {
        return MinutesIvcSection::where('team_id', $teamId)
            ->orderBy('order')
            ->get();
    }

    /**
     * Obtiene una sección específica por orden (1–16) y equipo.
     */
    public function getSectionByOrder(int $teamId, int $order)
    {
        return MinutesIvcSection::where('team_id', $teamId)
            ->where('order', $order)
            ->first();
    }

    /**
     * Obtiene todas las entradas (entries) de una sección dada.
     */
    public function getEntriesBySection(int $sectionId)
    {
        return MinutesIvcSectionEntry::where('minutes_ivc_section_id', $sectionId)
            ->orderBy('id')
            ->get();
    }
}
