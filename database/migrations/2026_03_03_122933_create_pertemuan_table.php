<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_perkuliahan_id')->constrained('kelas_perkuliahan')->onDelete('cascade');
            $table->integer('pertemuan_ke');
            $table->date('tanggal');
            $table->time('jam_mulai');    // Ambil nyawa dari table jadwal
            $table->time('jam_selesai');  // Ambil nyawa dari table jadwal
            $table->foreignId('lokasi_id')->constrained('lokasi'); // Biar tau radius & koordinat absen
            $table->text('materi')->nullable();
            $table->enum('status', ['dibuka', 'ditutup'])->default('ditutup');
            // Status ini buat 'Master Switch' Dosen kalau mau lock manual
            $table->timestamps();

            $table->unique(['kelas_perkuliahan_id', 'pertemuan_ke']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};
