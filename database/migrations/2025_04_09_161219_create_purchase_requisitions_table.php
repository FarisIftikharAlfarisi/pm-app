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
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('kode_requisition')->nullable();
            $table->unsignedBigInteger('request_id')->references('id')->on('requests')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->references('id')->on('projects')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->constrained()->onDelete('cascade');
            $table->string('nama_requisition');
            $table->string('jenis_requisition')->nullable(); // Barang, Jasa
            $table->dateTime('tanggal_requisition')->nullable();
            $table->string('status')->nullable(); // Approve, Reject, Pending
            $table->string('accounting_comment')->nullable();
            $table->unsignedBigInteger('accounting_id')->references('id')->on('users')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('purchase_requisitions');
    }
};
