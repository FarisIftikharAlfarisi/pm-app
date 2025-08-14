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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('kode_project')->unique(); // Kode unik untuk proyek
            $table->string('nama_project'); // Nama proyek
            $table->unsignedBigInteger('site_id')->references('id')->on('sites')->restrictOnDelete(); // Lokasi proyek, referensi ke tabel sites
            $table->text('deskripsi')->nullable(); // Deskripsi proyek
            $table->decimal('anggaran', 15, 2)->nullable(); // Anggaran proyek
            $table->enum('status', ['planning', 'in_progress', 'completed', 'on_hold', 'cancelled'])
                ->default('planning'); // Status proyek
            $table->enum('jenis_proyek', ['konstruksi', 'renovasi', 'pengadaan', 'lainnya'])
                ->default('konstruksi');
            $table->string('jenis_proyek_lainnya')->nullable(); // Jenis proyek lain jika tidak termasuk kategori yang ada
            $table->date('tanggal_mulai')->nullable(); // Tanggal mulai proyek
            $table->date('tanggal_selesai')->nullable(); // Tanggal selesai proyek
            $table->string('penanggung_jawab')->nullable(); // Nama penanggung jawab proyek
            $table->string('kontak_penanggung_jawab')->nullable(); // Kontak penanggung jawab proyek
            $table->string('klien')->nullable(); // Nama klien atau pihak yang terkait dengan proyek
            $table->string('kontak_klien')->nullable(); // Kontak klien atau pihak yang terkait dengan proyek
            $table->text('catatan')->nullable(); // Catatan tambahan untuk proyek
            $table->unsignedBigInteger('created_by')->references('id')->on('users')->onDelete('cascade'); // User yang membuat proyek
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
