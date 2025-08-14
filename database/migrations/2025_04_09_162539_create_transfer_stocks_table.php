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
        Schema::create('transfer_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transfer')->nullable();
            $table->unsignedBigInteger('asal_site_id')->references('id')->on('sites')->constrained()->onDelete('restrict');
            $table->dateTime('tanggal_transfer')->nullable();
            $table->unsignedBigInteger('tujuan_site_id')->references('id')->on('sites')->constrained()->onDelete('restrict');
            $table->dateTime('tanggal_sampai')->nullable();
            $table->string('approval_project_leader')->nullable(); // Approve, Reject, Pending
            $table->dateTime('project_leader_approval_date')->nullable();
            $table->unsignedBigInteger('project_leader_id')->nullable()->references('id')->on('users')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('creator_id')->nullable()->references('id')->on('users')->constrained()->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_stocks');
    }
};
