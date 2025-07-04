<?php

namespace App\Observers;

use App\Models\AnesthesiaSheet;
use Illuminate\Support\Facades\DB;

class AnesthesiaSheetObserver
{
    public function updating(AnesthesiaSheet $sheet)
    {
        // Si ya se descontó, nada que hacer
        if ($sheet->consumed) {
            return;
        }

        // Detecta transición opened → closed
        $oldStatus = $sheet->getOriginal('status');
        $newStatus = $sheet->status;

        if ($oldStatus === 'opened' && $newStatus === 'closed') {
            // Descontar en una transacción
            DB::transaction(function () use ($sheet) {
                foreach ($sheet->anesthesiaItems as $item) {
                    $inventory = $item->inventory;
                    // Asume que tienes un campo `quantity`
                    $inventory->decrement('quantity', $item->dose_measure);
                }
                // Marca como consumido para no repetir
                $sheet->consumed = true;
                // Guarda sin disparar de nuevo el observer updating
                $sheet->saveQuietly();
            });
        }
    }
    /**
     * Handle the AnesthesiaSheet "created" event.
     */
    public function created(AnesthesiaSheet $anesthesiaSheet): void
    {
        //
    }

    /**
     * Handle the AnesthesiaSheet "updated" event.
     */
    public function updated(AnesthesiaSheet $anesthesiaSheet): void
    {
        //
    }

    /**
     * Handle the AnesthesiaSheet "deleted" event.
     */
    public function deleted(AnesthesiaSheet $anesthesiaSheet): void
    {
        //
    }

    /**
     * Handle the AnesthesiaSheet "restored" event.
     */
    public function restored(AnesthesiaSheet $anesthesiaSheet): void
    {
        //
    }

    /**
     * Handle the AnesthesiaSheet "force deleted" event.
     */
    public function forceDeleted(AnesthesiaSheet $anesthesiaSheet): void
    {
        //
    }
}
