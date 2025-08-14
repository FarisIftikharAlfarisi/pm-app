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
        Schema::create('pembelian_barang_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_Pembelian')->references('id')->on('pembelian_barangs')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('ID_Barang')->references('id')->on('barangs')->constrained()->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('harga_satuan');
            $table->integer('total_harga');
            $table->string('status');
            $table->unsignedBigInteger('Kode_Karyawan')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_barang_details');
    }
};
