<?php

namespace App\Services;

use App\Models\ChecklistItem;
use App\Models\ChecklistItemAnswer;
use Illuminate\Support\Facades\DB;

class ChecklistItemAnswerService
{
    /**
     * Califica un ChecklistItem y registra la respuesta.
     *
     * @param int $itemId
     * @param string $calificacion ('cumple' o 'no cumple')
     * @return ChecklistItemAnswer
     * @throws \Exception
     */
    public function calificarChecklistItem($itemId, $calificacion)
    {
        // Valida que la calificación sea correcta
        if (!in_array($calificacion, ['cumple', 'no cumple'])) {
            throw new \InvalidArgumentException('Calificación no válida. Debe ser "cumple" o "no cumple".');
        }

        // Verifica que el ChecklistItem exista
        $checklistItem = ChecklistItem::find($itemId);

        if (!$checklistItem) {
            throw new \Exception('El ChecklistItem no existe.');
        }

        return DB::transaction(function () use ($checklistItem, $calificacion) {
            // Registra o actualiza la respuesta del ChecklistItem
            $respuesta = ChecklistItemAnswer::updateOrCreate(
                ['checklist_item_id' => $checklistItem->id],
                ['calificacion' => $calificacion]
            );

            // Si la calificación es 'no cumple', inicia un plan de mejora (opcional)
            if ($calificacion === 'no cumple' && !$checklistItem->planesDeMejora()->exists()) {
                $checklistItem->planesDeMejora()->create([
                    'estado' => 'pendiente', // Puedes ajustar los valores según tu lógica
                    'descripcion' => 'Plan de mejora generado automáticamente debido a calificación negativa.',
                ]);
            }

            return $respuesta;
        });
    }
}
