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
        Schema::create('bill_of_material_barangs', function (Blueprint $table) {
           $table->id();

            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');

            $table->string('kode_bom')->unique();
            $table->string('nama_bom');
            $table->decimal('quantity', 10, 2);
            $table->string('unit_of_measure');

            $table->enum('status', ['DRAFT', 'ACTIVE', 'ARCHIVED'])->default('DRAFT');

            $table->unsignedBigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('catatan_produksi')->nullable();

            $table->integer('estimasi_waktu_produksi')->nullable();
            $table->string('satuan_estimasi_waktu_produksi')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_of_material_barangs');
    }
};
