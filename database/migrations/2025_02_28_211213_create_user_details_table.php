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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_Pengguna')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->string('nomor_telepon')->nullable();
            $table->string('alamat')->nullable();
            $table->string('jabatan')->nullable();
            $table->unsignedBigInteger('Kode_Site')->references('id')->on('sites')->constrained()->onDelete('cascade');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
