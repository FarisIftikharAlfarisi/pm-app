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
        Schema::create('business_documents', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dokumen');
            $table->unsignedBigInteger('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->string('nama_dokumen');
            $table->string('jenis_dokumen')->nullable();
            $table->string('file_path');
            $table->enum('project_leader_approval', ['approve','reject', 'hold'])->default('hold');
            $table->enum('accounting_approval', ['approve','reject', 'hold'])->default('hold');
            $table->softDeletes();
            $table->unsignedBigInteger('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_documents');
    }
};
