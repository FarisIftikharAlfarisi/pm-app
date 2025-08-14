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
        Schema::create('budgetings', function (Blueprint $table) {
            $table->id();
            $table->string('Kode_Budgeting')->unique();
            $table->string('Nama_Budgeting');
            $table->string('Jenis_Budgeting');
            $table->integer('Total_Anggaran');
            $table->integer('Sisa_Anggaran');
            $table->string('Keterangan');
            $table->dateTime('Tanggal');
            $table->unsignedBigInteger('Kode_Karyawan')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgetings');
    }
};
