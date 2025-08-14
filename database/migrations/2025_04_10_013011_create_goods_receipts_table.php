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
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_goods_receipt')->unique();
            $table->unsignedBigInteger('purchase_order_id')->references('id')->on('purchase_orders')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('purchase_requisition_id')->references('id')->on('purchase_requisitions')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('site_request_id')->references('id')->on('site_requests')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->constrained()->onDelete('restrict');

            $table->string('approval_project_leader')->nullable(); // Approve, Reject, Pending
            $table->string('approval_accounting')->nullable(); // Approve, Reject, Pending

            $table->string('project_leader_comment')->nullable();
            $table->string('accounting_comment')->nullable();

            $table->unsignedBigInteger('project_leader_id')->nullable()->references('id')->on('users')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('accounting_id')->nullable()->references('id')->on('users')->constrained()->onDelete('restrict');
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
        Schema::dropIfExists('goods_receipts');
    }
};
