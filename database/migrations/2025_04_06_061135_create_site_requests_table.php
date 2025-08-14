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
        Schema::create('site_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->references('id')->on('projects')->constrained()->onDelete('cascade');
            $table->string('kode_request')->nullable();
            $table->string('nama_request');
            $table->string('jenis_request')->nullable(); // Barang, Jasa
            $table->string('approval_status')->nullable(); // Approve, Reject, Pending

            $table->string('approval_accounting_status')->nullable(); // Approve, Reject, Pending
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('approval_accounting_id')->nullable()->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('site_requests');
    }
};
