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
        Schema::create('penerimaan_barang_warehouse_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penerimaan_barang_warehouse_id')->references('id')->on('penerimaan_barang_warehouses')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('barang_id')->references('id')->on('barangs')->constrained()->onDelete('cascade');
            $table->integer('jumlah')->nullable();
            $table->unsignedBigInteger('satuan_id')->nullable()->references('id')->on('item_units')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id')->nullable()->references('id')->on('suppliers')->constrained()->onDelete('cascade');
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('creator_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_barang_warehouse_details');
    }
};
