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
        Schema::create('transfer_stock_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_stock_id')->references('id')->on('transfer_stocks')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('barang_id')->references('id')->on('barangs')->constrained()->onDelete('cascade');
            $table->integer('jumlah')->nullable();
            $table->string('approved_by_project_leader')->nullable();
            $table->string('approved_by_accounting')->nullable();
            $table->string('project_leader_comment')->nullable();
            $table->unsignedBigInteger('project_leader_id')->nullable()->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->dateTime('project_leader_approval_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_stock_details');
    }
};
