<?php

use App\Models\ManagementIndicator;
use App\Models\Role;
use App\Models\Team;
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
        Schema::create('management_indicator_team', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->cascadeOndelete();
            $table->foreignIdFor(ManagementIndicator::class)->cascadeOndelete();
            $table->foreignIdFor(Role::class)->constrained();
            $table->enum('periodicity', ['Diario', 'Mensual', 'Bimestral', 'Trimestral', 'Semestral', 'Anual']);
            $table->float('indicator_goal')->nullable(); // Meta personalizada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_indicator_team');
    }
};
