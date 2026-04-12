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
        Schema::create('supplier_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('name'); // nama barang menurut supplier
            $table->decimal('price', 15, 2); // harga beli terakhir
            $table->foreignId('unit_id')->constrained()->onDelete('restrict'); // satuan tidak boleh dihapus jika dipakai
            $table->timestamps();

            $table->unique(['supplier_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_items');
    }
};
