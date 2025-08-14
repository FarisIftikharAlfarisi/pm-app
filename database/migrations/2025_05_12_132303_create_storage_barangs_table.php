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
        Schema::create('storage_barangs', function (Blueprint $table) {
            $table->id();

            // Lokasi penyimpanan (di gudang atau lokasi proyek)
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->restrictOnDelete();

            // Informasi posisi fisik
            $table->string('section')->nullable(); // e.g., A, B, ColdRoom, dll
            $table->string('rak')->nullable();     // e.g., R1, R2
            $table->decimal('max_capacity');     // Maksimal jumlah yang bisa ditampung
            $table->enum('capacity_unit', ['kg', 'pcs', 'ltr', 'm3'])->default('pcs'); // Satuan kapasitas

            $table->boolean('is_overcapacity')->default(false); // True jika overcapacity

            $table->text('keterangan')->nullable(); // Misalnya "suhu ruangan", atau "hanya untuk bahan cair"

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_barangs');
    }
};
