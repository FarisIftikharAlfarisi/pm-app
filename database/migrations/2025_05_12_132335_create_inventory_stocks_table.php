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
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id')->references('id')->on('barangs')->cascadeOnDelete();
            $table->unsignedBigInteger('store_at')->nullable()->references('id')->on('storage_barangs')->cascadeOnDelete();
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->restrictOnDelete();
            $table->decimal('quantity', 10, 2); // Support desimal (e.g., 0.5 kg)
            $table->string('batch_number')->nullable(); // Untuk tracking kualitas
            $table->date('expiry_date')->nullable(); // Untuk material kadaluarsa
            $table->enum('status', ['available', 'reserved', 'damaged'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_stocks');
    }
};
