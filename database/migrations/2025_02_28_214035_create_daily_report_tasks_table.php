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
        Schema::create('daily_report_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_to_do_id')->references('id')->on('task_to_dos')->onDelete('cascade');
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->restrictOnDelete();
            $table->string('kode_task')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_report_tasks');
    }
};
