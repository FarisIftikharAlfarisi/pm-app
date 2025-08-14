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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_purchase_order')->nullable();
            $table->string('nama_purchase_order')->nullable();
            $table->dateTime('tanggal_purchase_order')->nullable();
            $table->dateTime('estimasi_sampai')->nullable();
            $table->unsignedBigInteger('site_request_id')->references('id')->on('site_requests')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('project_id')->refrences('id')->on('projects')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->constrained()->onDelete('restrict');
             $table->unsignedBigInteger('supplier_id')->nullable()->references('id')->on('suppliers')->constrained()->onDelete('cascade');
            $table->string('approval_accounting_status')->nullable(); // Approve, Reject, Pending
            $table->string('accounting_comment')->nullable();
            $table->unsignedBigInteger('accounting_id')->nullable()->references('id')->on('users')->constrained()->onDelete('restrict');
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
        Schema::dropIfExists('purchase_orders');
    }
};
