<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Actualiza el ENUM de status en external_orders para soportar nuevos estados
     * del flujo de trabajo de órdenes externas.
     * 
     * Estados anteriores: ['pending', 'no_candidates', 'notified', 'assigned', 'delivered', 'rejected', 'cancelled']
     * Nuevos estados: ['NOTIFIED', 'CLAIMED', 'PREPARATION', 'IN_TRANSIT', 'DELIVERED_VERIFIED', 'REJECTED', 'CANCELLED']
     * 
     * Nota: MySQL no permite modificar directamente un ENUM, por lo que:
     * 1. Se renombra la columna status a status_old
     * 2. Se crea una nueva columna status con los nuevos valores
     * 3. Se migran los datos de la columna anterior
     * 4. Se elimina la columna status_old
     */
    public function up(): void
    {
        Schema::table('external_orders', function (Blueprint $table) {
            // Cambiar nombre de columna status a status_old
            $table->renameColumn('status', 'status_old');
        });

        Schema::table('external_orders', function (Blueprint $table) {
            // Crear nueva columna status con los nuevos valores enum
            $table->enum('status', [
                'PENDING',
                'NO_CANDIDATES',      // No hay equipos candidatos disponibles
                'NOTIFIED',           // Orden disponible para candidatos
                'CLAIMED',            // Orden tomada por un equipo
                'PREPARATION',        // Equipo preparando venta y OTP
                'IN_TRANSIT',         // Orden en camino
                'DELIVERED_VERIFIED', // Entregada y OTP verificado
                'CANCELLED',           // Cancelada
                'REJECTED',           // Rechazada por equipo
            ])->default('PENDING')->after('team_id');
        });

        // Eliminar columna antigua
        Schema::table('external_orders', function (Blueprint $table) {
            $table->dropColumn('status_old');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('external_orders', function (Blueprint $table) {
            // Cambiar nombre a status_old
            $table->renameColumn('status', 'status_old');
        });

        Schema::table('external_orders', function (Blueprint $table) {
            // Restaurar enum antiguo
            $table->enum('status', ['pending', 'no_candidates', 'notified', 'assigned', 'delivered', 'rejected', 'cancelled'])
                  ->default('pending')
                  ->after('team_id');
        });

        // Migrar datos de vuelta
        DB::statement("UPDATE external_orders SET status = 'notified' WHERE status_old IN ('NOTIFIED')");
        DB::statement("UPDATE external_orders SET status = 'assigned' WHERE status_old = 'CLAIMED'");
        DB::statement("UPDATE external_orders SET status = 'delivered' WHERE status_old = 'DELIVERED_VERIFIED'");
        DB::statement("UPDATE external_orders SET status = 'rejected' WHERE status_old = 'REJECTED'");
        DB::statement("UPDATE external_orders SET status = 'cancelled' WHERE status_old = 'CANCELLED'");

        // Eliminar columna antigua
        Schema::table('external_orders', function (Blueprint $table) {
            $table->dropColumn('status_old');
        });
    }
};
