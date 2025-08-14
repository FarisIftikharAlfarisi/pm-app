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
        Schema::create('site_request_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_request_id')->references('id')->on('site_requests')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('barang_id')->references('id')->on('barangs')->constrained()->onDelete('cascade');
            $table->integer('jumlah')->nullable();
            $table->unsignedBigInteger('satuan_id')->nullable()->references('id')->on('item_units')->constrained()->onDelete('cascade');
            $table->string('approval_accounting_status')->nullable(); // Approve, Reject, Pending
            $table->string('accounting_comment')->nullable();
            $table->unsignedBigInteger('accounting_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('accounting_approval_date')->nullable();
            $table->string('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_request_details');
    }
};
