<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            // Elimina el índice único antiguo.
            $table->dropUnique('permissions_name_guard_name_unique');

            // Crea un nuevo índice único.
            $table->unique(['name', 'guard_name', 'team_id'], 'permissions_name_guard_name_team_id_unique');
        });
    }

    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            // Revierte el cambio: elimina el índice que incluye team_id
            $table->dropUnique('permissions_name_guard_name_team_id_unique');

            // Y vuelve a crear el índice único original
            $table->unique(['name', 'guard_name'], 'permissions_name_guard_name_unique');
        });
    }

};
