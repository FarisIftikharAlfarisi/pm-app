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
        Schema::create('goods_receipts_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goods_receipt_id')->references('id')->on('goods_receipts')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('barang_id')->references('id')->on('barangs')->constrained()->onDelete('cascade');
            $table->integer('jumlah')->nullable();
            $table->string('approved_by_project_leader')->nullable();
            $table->string('approved_by_accounting')->nullable();
            $table->string('project_leader_comment')->nullable();
            $table->string('accounting_comment')->nullable();
            $table->unsignedBigInteger('project_leader_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('accounting_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('project_leader_approval_date')->nullable();
            $table->dateTime('accounting_approval_date')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipts_details');
    }
};
