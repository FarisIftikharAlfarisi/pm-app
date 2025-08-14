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
        Schema::create('auth_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->string('username');
            $table->string('email');
            $table->dateTime('action_time');
            $table->string('action_type'); // Login, Logout, Failed Login, etc.
            $table->string('ip_address')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('status')->nullable(); // 1 = Success, 0 = Failed
            $table->string('error_message')->nullable();
            $table->integer('time_spent')->nullable(); // Optional, to track time spent in the system
            $table->string('session_id')->nullable(); // Untuk tracking session
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_logs');
    }
};
