<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Task To Do merupakan tabel yang merepresentasikan WBS (Work Breakdown Structure) untuk proyek.
     */
    public function up(): void
    {
        Schema::create('task_to_dos', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->references('id')->on('projects')->onDelete('cascade');

            // self referencing
            $table->unsignedBigInteger('parent_id')->references('id')->on('task_to_dos')->onDelete('restrict')->nullable();
           $table->unsignedBigInteger('accounting_id')->nullable()->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('project_leader_id')->nullable()->references('id')->on('users')->onDelete('cascade');


            // Detail task
            $table->string('kode_task')->unique();         // Kode unik task
            $table->string('nama_task');                   // Nama task
            $table->text('deskripsi')->nullable();         // Deskripsi
            $table->enum('type', ['general', 'procurement', 'production', 'delivery', 'inspection'])
                ->default('general');                    // Jenis task

            $table->unsignedInteger('sort_order')->nullable();   // Urutan task dalam parent

            // Waktu
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->integer('buffer_days')->default(3)->nullable();            // default 3 hari sebelum start

            // Status
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])
                ->default('pending')->nullable();
            $table->enum('priority_level', ['1', '2', '3'])->nullable(); // 1: tinggi
            $table->boolean('is_milestone')->default(false);       // apakah task ini milestone?
            $table->boolean('is_critical_path')->default(false);   // CPM marker

            // Persetujuan
            $table->boolean('is_locked')->default(false);          // Project locked
            $table->enum('accounting_approve', ['pending', 'approved', 'rejected'])
                ->default('pending')->nullable();// disetujui oleh user akuntansi
            $table->enum('project_leader_approve', ['pending', 'approved', 'rejected'])
                ->default('pending')->nullable();// disetujui oleh user project leader

            // User yang membuat task
            $table->unsignedBigInteger('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');


            // Soft delete & timestamps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_to_dos');
    }
};
