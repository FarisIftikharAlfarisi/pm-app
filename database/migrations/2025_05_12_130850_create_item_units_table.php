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
        Schema::create('item_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id')->references('id')->on('barangs')->onDelete('cascade');
            $table->string('unit_name'); // e.g., 'kg', 'pcs', 'meter'
            $table->decimal('conversion_factor')->nullable(); // e.g., 1 kg = 1000 gram → factor = 1000
            $table->string('deskripsi_konversi')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_units');
    }
};
