<?php

use App\Models\AnesthesiaSheet;
use App\Models\Inventory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anesthesia_sheet_items', function (Blueprint $table) {
            $table->id();
        $table->foreignIdFor(AnesthesiaSheet::class)
            ->constrained()
            ->cascadeOnDelete();
        $table->string('phase', 15); // 'pre_anesthesia', 'intraoperative', 'post_anesthesia'
        $table->foreignIdFor(Inventory::class); // drug
        $table->smallInteger('dose_per_kg'); // dose (mg) per kilogram of body weight
        $table->smallInteger('dose_measure'); // amoun of the drug administered: 'dose_per_kg * kg', int
        $table->string('dose_measure_unit', 15); // measurement unit of the dose: 'tab', 'mg', 'ml', 'units', etc.
        $table->enum('administration_route', ['iv', 'im', 'subcutaneous', 'intradermic', 'oral', 'rectal', 'respiratory', 'other'])->default('iv'); // oral, intravenous, intramuscular, subcutaneous, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anesthesia_sheet_items');
    }
};
