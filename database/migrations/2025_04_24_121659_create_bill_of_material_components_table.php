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
        Schema::create('bill_of_material_components', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('bom_id');
            $table->foreign('bom_id')->references('id')->on('bill_of_material_barangs')->onDelete('cascade');

            $table->unsignedBigInteger('bahan_baku_id');
            $table->foreign('bahan_baku_id')->references('id')->on('barangs')->onDelete('restrict');

            $table->decimal('quantity', 10, 2);
            $table->string('unit_of_measure');

            $table->decimal('toleransi_quantity', 10, 2)->default(0); // 0–10% toleransi

            $table->integer('waktu_produksi_per_unit')->nullable(); // dalam menit

            $table->unsignedBigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();

            $table->unique(['bom_id', 'bahan_baku_id']); // Tidak boleh duplikat bahan dalam 1 BOM
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_of_material_components');
    }
};
