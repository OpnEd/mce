<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnesthesiaSheetItem extends Model
{
    /** @use HasFactory<\Database\Factories\AnesthesiaSheetItemFactory> */
    use HasFactory;
    
    protected $fillable = [
        'anesthesia_sheet_id',
        'phase', // 'pre_anesthesia', 'intraoperative', 'post_anesthesia'
        'inventory_id', // drug
        'dose_per_kg', // dose (mg) per kilogram of body weight
        'dose_measure', // amoun of the drug administered: 'dose_per_kg * kg', int
        'dose_measure_unit', // measurement unit of the dose: 'tab', 'mg', 'ml', 'units', etc.
        'administration_route', // oral, intravenous, intramuscular, subcutaneous, etc.
    ];
    
    public function anesthesiaSheet(): BelongsTo
    {
        return $this->belongsTo(AnesthesiaSheet::class);
    }
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
    public function getDoseMeasureAttribute($value)
    {
        return $value ?? 0; // Ensure dose_measure is never null
    }
    public function getDosePerKgAttribute($value)
    {
        return $value ?? 0; // Ensure dose_per_kg is never null
    }
    public function getPhaseAttribute($value)
    {
        return $value ?? 'pre_anesthesia'; // Default to 'pre_anesthesia' if phase is null
    }
    public function getViaAttribute($value)
    {
        return $value ?? 'iv'; // Default to 'oral' if via is null
    }

}
