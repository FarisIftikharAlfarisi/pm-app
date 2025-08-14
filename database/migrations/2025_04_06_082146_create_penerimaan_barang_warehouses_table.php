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
        Schema::create('penerimaan_barang_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penerimaan_barang')->nullable();
            $table->string('nama_penerimaan_barang')->nullable();
            $table->unsignedBigInteger('penerimaan_barang_id')->references('id')->on('penerimaan_barangs')->constrained()->onDelete('cascade');
            // untuk relasi ke good receipt
            $table->unsignedBigInteger('site_request_id')->references('id')->on('site_requests')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_id')->references('id')->on('sites')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('purchase_order_id')->references('id')->on('purchase_orders')->constrained()->onDelete('cascade');
            $table->string('approval_project_leader')->nullable(); // Approve, Reject, Pending
            $table->string('approval_accounting')->nullable(); // Approve, Reject, Pending
            $table->string('project_leader_comment')->nullable();
            $table->string('accounting_comment')->nullable();
            $table->unsignedBigInteger('project_leader_id')->nullable()->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('accounting_id')->nullable()->references('id')->on('users')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('penerimaan_barang_warehouses');
    }
};
