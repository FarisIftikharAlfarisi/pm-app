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
        Schema::create('kebutuhan_barang_wbs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_to_do_id'); // Relasi ke task_to_do
            $table->unsignedBigInteger('barang_id'); // Relasi ke barang
            $table->unsignedBigInteger('satuan_id')->nullable(); // Satuan barang ke unit items

            $table->decimal('jumlah', 10, 2); // Jumlah barang yang dibutuhkan

            $table->softDeletes(); // Untuk soft delete
            $table->timestamps();

            // relasi
            $table->foreign('task_to_do_id')->references('id')->on('task_to_dos')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
            $table->foreign('satuan_id')->references('id')->on('item_units')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebutuhan_barang_wbs');
    }
};
