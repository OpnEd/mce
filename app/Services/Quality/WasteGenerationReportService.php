<?php

namespace App\Services\Quality;

use App\Models\Quality\WasteGenerationReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WasteGenerationReportService
{
    /**
     * Generar PDF del informe de residuos
     */
    public static function generarPDF(WasteGenerationReport $informe)
    {
        // Saneamiento: limpiar cadenas inválidas para evitar errores de json_encode (logs/PDF)
        $informe_array = $informe->toArray();
        $informe_sane = self::sanitizeUtf8($informe_array);

        // Reemplazar sólo los campos susceptibles en el modelo antes de render/log
        if (array_key_exists('descripcion', $informe_sane)) {
            $informe->descripcion = $informe_sane['descripcion'];
        }
        if (array_key_exists('resumen', $informe_sane)) {
            $informe->resumen = $informe_sane['resumen'];
        }

        $datos = self::prepararDatos($informe);

        Log::info('Datos informe para PDF', [
            'descripcion' => $informe->descripcion,
            'resumen' => $informe->resumen,
        ]);

        $pdf = Pdf::loadView('informes.residuos-pdf', $datos)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('defaultMediaType', 'print')
            ->setOption('isFontSubsettingEnabled', true)
            ->setOption('dpi', 96)
            ->setOption('logOutputFile', storage_path('logs/dompdf.log'));

        return $pdf;
    }

    /**
     * Descargar PDF
     */
    public static function descargarPDF(WasteGenerationReport $informe)
    {
        $pdf = self::generarPDF($informe);
        $nombreArchivo = "{$informe->numero_informe}.pdf";

        return $pdf
            ->download($nombreArchivo)
            ->header('Content-Type', 'application/pdf; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$nombreArchivo}\"");
    }

    /**
     * Guardar PDF en storage
     */
    public static function guardarPDF(WasteGenerationReport $informe): string
    {
        $pdf = self::generarPDF($informe);
        $nombreArchivo = "informes/{$informe->numero_informe}.pdf";

        Storage::put($nombreArchivo, $pdf->output());

        return $nombreArchivo;
    }

    /**
     * Preparar datos para la vista del PDF
     */
    private static function prepararDatos(WasteGenerationReport $informe): array
    {
        $resumen = $informe->resumen ?? [];

    // Normalizar strings a UTF‑8 de forma recursiva
    $normalizar = function ($value) use (&$normalizar) {
        if (is_array($value)) {
            return array_map($normalizar, $value);
        }
        if (is_string($value)) {
            return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }
        return $value;
    };

    $resumen = $normalizar($resumen);
    $descripcion = $informe->descripcion ? $normalizar($informe->descripcion) : null;

        return [
            'informe' => $informe,
            'resumen' => $resumen,
            'fecha_actual' => now()->format('d/m/Y H:i'),
            'generado_por' => self::sanitizeUtf8(Auth::user()?->name ?? $informe->user->name),
            'porcentajes' => [
                'reciclable' => $resumen['porcentaje_reciclable'] ?? 0,
                'ordinario' => $resumen['porcentaje_ordinario'] ?? 0,
                'guardian' => $resumen['porcentaje_guardian'] ?? 0,
                'bolsa_roja' => $resumen['porcentaje_bolsa_roja'] ?? 0,
            ],
            'promedios' => [
                'reciclable' => $resumen['promedio_reciclable'] ?? 0,
                'ordinario' => $resumen['promedio_ordinario'] ?? 0,
                'guardian' => $resumen['promedio_guardian'] ?? 0,
                'bolsa_roja' => $resumen['promedio_bolsa_roja'] ?? 0,
            ],
        ];
    }

    /**
     * Sanea recursivamente cadenas a UTF-8, eliminando bytes inválidos.
     * Acepta strings, arrays y objetos (convierte objetos con toArray cuando está disponible).
     */
    private static function sanitizeUtf8(mixed $value): mixed
    {
        if (is_array($value)) {
            $out = [];
            foreach ($value as $k => $v) {
                $out[$k] = self::sanitizeUtf8($v);
            }
            return $out;
        }

        if (is_object($value)) {
            if (method_exists($value, 'toArray')) {
                return self::sanitizeUtf8($value->toArray());
            }
            // For plain objects, cast to array then sanitize
            return self::sanitizeUtf8((array) $value);
        }

        if (is_string($value)) {
            $s = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
            return $s === false ? '' : $s;
        }

        return $value;
    }
}
