<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengambil semester yang sedang aktif
        $semester = Semester::where('status', 'aktif')->first();

        if (!$semester) {
            $this->command->error('❌ Semester aktif tidak ditemukan! Jalankan SemesterSeeder dulu.');
            return;
        }

        // Daftar 5 MK Umum dan 5 MK Praktikum (Prodi Budidaya Tanaman Perkebunan)
        $daftarMatkul = [
            // 5 Mata Kuliah Umum
            ['nama' => 'Pendidikan Agama'],
            ['nama' => 'Pendidikan Pancasila dan Kewarganegaraan'],
            ['nama' => 'Bahasa Indonesia'],
            ['nama' => 'Bahasa Inggris Konversasi'],
            ['nama' => 'Matematika Terapan'],

            // 5 Mata Kuliah Praktikum (Budidaya Tanaman Perkebunan)
            ['nama' => 'Praktikum Botani Perkebunan'],
            ['nama' => 'Praktikum Kesuburan Tanah dan Pemupukan'],
            ['nama' => 'Praktikum Budidaya Tanaman Kelapa Sawit'],
            ['nama' => 'Praktikum Perlindungan Tanaman Perkebunan'],
            ['nama' => 'Praktikum Teknologi Pengolahan Hasil Perkebunan'],
        ];

        // Digunakan untuk menampung angka random agar Kode MK tidak duplikat (Unique)
        $usedNumbers = [];

        foreach ($daftarMatkul as $matkul) {
            // Generate 3 digit angka random yang unik untuk kode "BTPxxx"
            do {
                $randomNumber = rand(100, 999);
            } while (in_array($randomNumber, $usedNumbers));

            $usedNumbers[] = $randomNumber;
            $kodeMk = 'BTP' . $randomNumber;

            // Create Mata Kuliah
            MataKuliah::create([
                'kode_mk' => $kodeMk,
                'nama' => $matkul['nama'],
                'sks' => rand(2, 3), // SKS random antara 2 atau 3
                'semester_id' => $semester->id, // Dikaitkan ke semester aktif
            ]);
        }

        $this->command->info('✓ ' . count($daftarMatkul) . ' mata kuliah (5 Umum, 5 Praktikum BTP) berhasil dibuat untuk ' . $semester->nama);
    }
}
