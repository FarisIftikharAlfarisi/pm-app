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
        Schema::create('purchase_requisition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pr_id')->references('id')->on('purchase_requisitions')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('barang_id')->references('id')->on('barangs')->constrained()->onDelete('cascade');
            $table->integer('jumlah')->nullable();
            $table->string('satuan')->nullable(); // Satuan barang, bisa diambil dari item_units
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisition_details');
    }
};
