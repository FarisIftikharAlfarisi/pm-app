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
        Schema::create('supplier_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
            $table->integer('lama_waktu_pengiriman')->nullable();
            $table->string('satuan_lama_waktu_pengiriman')->nullable();
            $table->integer('kuantitas_minimum')->nullable();
            $table->unsignedBigInteger('satuan_kuantitas_minimum')->references('id')->on('item_units')->onDelete('cascade')->nullable();
            $table->integer('jarak_pengiriman')->nullable();
            $table->string('satuan_jarak_pengiriman')->nullable();
            $table->decimal('harga', 10, 2)->nullable();
            $table->decimal('harga_beli', 10, 2)->nullable();
            $table->decimal('diskon', 5, 2)->default(0)->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_barangs');
    }
};
