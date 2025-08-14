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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->enum('movement_type', ['IN', 'OUT', 'TRANSFER', 'ADJUSTMENT']);
            $table->unsignedBigInteger('barang_id')->references('id')->on('barangs');
            $table->decimal('quantity', 10, 2);
            $table->unsignedBigInteger('from_site_id')->nullable()->references('id')->on('sites');
            $table->unsignedBigInteger('to_site_id')->nullable()->references('id')->on('sites');
            $table->unsignedBigInteger('reference_id'); // ID dari transaksi terkait
            $table->string('reference_type'); // e.g., 'App\Models\TransferStock'
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('creator_id')->references('id')->on('users'); // Siapa yang input
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
