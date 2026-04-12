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
        Schema::table('purchase_items', function (Blueprint $table) {
             $table->foreignId('supplier_item_id')->nullable()->after('purchase_id');
                $table->foreign('supplier_item_id')
                  ->references('id')
                  ->on('supplier_items')
                  ->onDelete('set null'); // jika supplier_item dihapus, jadi null (historis tetap ada)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            //
        });
    }
};
