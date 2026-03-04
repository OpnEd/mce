<?php

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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('establishment_id', 15)
                ->nullable()
                ->comment('Identificación en la base de datos de la SDS');

            $table->string('registration_number', 15)
                ->nullable()
                ->comment('Negocios saludables, negocios rentables');

            $table->string('team_name', 255)
                ->nullable()
                ->comment('Razón social');

            $table->string('location_1', 255)
                ->nullable()
                ->comment('Nombre o identificación de sede, si lo es');

            $table->string('location_2', 255)
                ->nullable()
                ->comment('Si se encuentra por ejemplo dentro de un centro comercial o conjunto residencial');

            $table->string('town', 255)->nullable()->comment('Localidad');
            $table->string('upz', 255)->nullable()->comment('Unidad de planeación zonal');
            $table->string('neighborhood', 255)->nullable();

            $table->string('phone_number_1', 35)->nullable();
            $table->string('phone_number_2', 35)->nullable();

            $table->string('legal_representative_name', 255)->nullable();

            $table->enum('legal_representative_doc_type', ['CC', 'CE', 'PEP'])
                ->nullable();

            $table->string('legal_representative_doc_num', 35)->nullable();

            $table->text('operating_hours')
                ->nullable()
                ->comment('Horario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn([
                'establishment_id',
                'registration_number',
                'team_name',
                'location_1',
                'location_2',
                'town',
                'upz',
                'neighborhood',
                'phone_number_1',
                'phone_number_2',
                'legal_representative_name',
                'legal_representative_doc_type',
                'legal_representative_doc_num',
                'operating_hours',
            ]);
        });
    }
};
