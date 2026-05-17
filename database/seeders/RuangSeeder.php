<?php

namespace Database\Seeders;

use App\Models\Ruang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gedungDefault = 'Gedung Produksi Pertanian';

        $ruangans = [
            // ================= 5 RUANG KELAS =================
            [
                'nama' => 'Ruang 1.1',
                'kapasitas' => 20, // Kecil
            ],
            [
                'nama' => 'Ruang 1.2',
                'kapasitas' => 20, // Kecil
            ],
            [
                'nama' => 'Ruang 1.3',
                'kapasitas' => 20, // Kecil
            ],
            [
                'nama' => 'Ruang 2.1',
                'kapasitas' => 80, // Besar
            ],
            [
                'nama' => 'Auditorium',
                'kapasitas' => 80, // Besar
            ],

            // ================= 5 RUANG LABORATORIUM =================
            // 4 Lab Kecil (Kapasitas 20)
            [
                'nama' => 'Lab Kultur Jaringan',
                'kapasitas' => 20, // Kecil
            ],
            [
                'nama' => 'Lab Perlindungan Tanaman (Hama & Penyakit)',
                'kapasitas' => 20, // Kecil
            ],
            [
                'nama' => 'Lab Analisis Tanah dan Unsur Hara',
                'kapasitas' => 20, // Kecil
            ],
            [
                'nama' => 'Lab Fisiologi dan Pemuliaan Tanaman',
                'kapasitas' => 20, // Kecil
            ],
            // 1 Lab Besar (Kapasitas 80)
            [
                'nama' => 'Lab Lapang dan Smart Greenhouse',
                'kapasitas' => 80, // Besar
            ],
        ];

        foreach ($ruangans as $ruang) {
            Ruang::create([
                'nama' => $ruang['nama'],
                'kapasitas' => $ruang['kapasitas'],
                'gedung' => $gedungDefault, // Semua otomatis masuk ke Gedung Produksi Pertanian
            ]);
        }

        $this->command->info('✓ ' . count($ruangans) . ' ruang (5 Kelas & 5 Lab Produksi Pertanian) berhasil dibuat.');
    }
}
