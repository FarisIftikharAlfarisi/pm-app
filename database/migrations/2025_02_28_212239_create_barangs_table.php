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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique(); // Standarkan penulisan snake_case
            $table->string('nama_barang');
            $table->enum('kategori', ['BAHAN_BAKU', 'AFTERCRAFT', 'JASA_INTERNAL', 'JASA_EXTERNAL','PERALATAN_UMUM', 'PERALATAN_KANTOR','PERALATAN_PROYEK'])->nullable();
            $table->string('merk')->nullable();
            $table->text('keterangan')->nullable(); // Ubah ke text untuk deskripsi panjang
            $table->boolean('is_visible')->default(true); // Boolean lebih eksplisit
            $table->string('foto_path')->nullable(); // Tambahan untuk dokumentasi visual
            $table->unsignedBigInteger('creator_id')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
