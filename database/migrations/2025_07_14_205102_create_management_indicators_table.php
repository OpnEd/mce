<?php

use App\Models\QualityGoal;
use App\Models\Role;
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
        Schema::create('management_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(QualityGoal::class)->constrained();
            $table->foreignIdFor(Role::class)->constrained();
            $table->string('name');
            $table->text('objective');
            $table->text('description');
            $table->enum('type', ['Cardinal', 'Porcentual'])->nullable();
            $table->enum('periodicity', ['Mensual', 'Bimestral', 'Trimestral', 'Semestral', 'Anual'])->nullable();
            $table->string('information_source')->nullable();
            $table->string('numerator');
            $table->smallInteger('denominator')->nullable()->default(null);
            $table->string('denominator_description')->nullable();
            $table->float('indicator_goal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_indicators');
    }
};
