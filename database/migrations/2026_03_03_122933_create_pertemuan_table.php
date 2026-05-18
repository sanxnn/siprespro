<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_perkuliahan_id')->constrained('kelas_perkuliahan')->onDelete('cascade');
            $table->integer('pertemuan_ke');
            $table->date('tanggal');
            $table->time('jam_mulai');    
            $table->time('jam_selesai');  
            $table->foreignId('lokasi_id')->constrained('lokasi'); 
            $table->text('materi')->nullable();
            $table->enum('status', ['dibuka', 'ditutup'])->default('ditutup');
            $table->boolean('is_manual')->default(false);
            $table->timestamps();
            $table->unique(['kelas_perkuliahan_id', 'pertemuan_ke']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};
