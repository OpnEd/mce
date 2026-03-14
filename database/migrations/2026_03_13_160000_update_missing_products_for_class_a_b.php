<?php

use App\Models\PurchaseItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('missing_products', function (Blueprint $table) {
            $table->boolean('is_selected')->default(false)->after('product_id');
            $table->boolean('requested_by_user')->default(false)->after('is_selected');
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'unknown'])
                ->default('unknown')
                ->after('requested_by_user');
            $table->foreignIdFor(PurchaseItem::class)
                ->nullable()
                ->after('stock_status')
                ->constrained()
                ->nullOnDelete();
        });

        if (Schema::hasColumn('missing_products', 'type')) {
            DB::table('missing_products')
                ->where('type', 'faltante_ordinario')
                ->update([
                    'is_selected' => true,
                    'requested_by_user' => false,
                    'stock_status' => 'in_stock',
                ]);

            DB::table('missing_products')
                ->where('type', 'faltante_efectivo')
                ->update([
                    'is_selected' => true,
                    'requested_by_user' => true,
                    'stock_status' => 'out_of_stock',
                ]);

            DB::table('missing_products')
                ->where('type', 'faltante_baja_rotacion')
                ->update([
                    'is_selected' => false,
                    'requested_by_user' => true,
                    'stock_status' => 'out_of_stock',
                ]);
        }

        if (Schema::hasColumn('missing_products', 'type')) {
            Schema::table('missing_products', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('missing_products', function (Blueprint $table) {
            $table->enum('type', ['faltante_ordinario', 'faltante_efectivo', 'faltante_baja_rotacion'])
                ->nullable()
                ->after('product_id');
        });

        DB::table('missing_products')
            ->where('is_selected', true)
            ->where('requested_by_user', false)
            ->update(['type' => 'faltante_ordinario']);

        DB::table('missing_products')
            ->where('is_selected', true)
            ->where('requested_by_user', true)
            ->where('stock_status', 'out_of_stock')
            ->update(['type' => 'faltante_efectivo']);

        DB::table('missing_products')
            ->where('is_selected', false)
            ->where('requested_by_user', true)
            ->where('stock_status', 'out_of_stock')
            ->update(['type' => 'faltante_baja_rotacion']);

        Schema::table('missing_products', function (Blueprint $table) {
            $table->dropForeign(['purchase_item_id']);
            $table->dropColumn([
                'purchase_item_id',
                'stock_status',
                'requested_by_user',
                'is_selected',
            ]);
        });
    }
};
