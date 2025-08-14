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
        Schema::create('produksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produksi');
            $table->unsignedBigInteger('barang_id')->references('id')->on('barangs')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->references('id')->on('projects')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('purchase_order_id')->references('id')->on('purchase_orders')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->string('nama_produksi');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksis');
    }
};
