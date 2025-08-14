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
        Schema::create('pembelian_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembelian');
            $table->string('supplier')->nullable();
            $table->integer('total_belanja')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->dateTime('tanggal_pembelian')->nullable();
            $table->string('bukti_pembayaran')->nullable(); // Menambahkan kolom untuk menyimpan path bukti pembayaran (foto)
            $table->string('approval_status')->nullable(); // Approve, Reject, Pending
            $table->unsignedBigInteger('project_leader_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('projet_leader_approval_date')->nullable();
            $table->string('projet_leader_comment')->nullable();

            $table->unsignedBigInteger('accounting_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('accounting_approval_date')->nullable();
            $table->string('accounting_comment')->nullable();

            $table->unsignedBigInteger('creator_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_barangs');
    }
};
