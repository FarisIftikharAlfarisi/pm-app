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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id')->references('id')->on('barangs');
            $table->unsignedBigInteger('site_id')->references('id')->on('sites');
            $table->decimal('quantity_before');
            $table->decimal('quantity_after');
            $table->string('reason'); // e.g., 'stock_opname', 'kerusakan'
            $table->unsignedBigInteger('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
