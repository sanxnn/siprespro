<?php

namespace Database\Seeders;

use App\Models\Golongan;
use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semester = Semester::where('status', 'aktif')->first();
        $golongans = Golongan::all();

        if (!$semester || $golongans->isEmpty()) {
            $this->command->error('Semester atau Golongan tidak ditemukan! Jalankan SemesterSeeder & GolonganSeeder dulu.');
            return;
        }

        $angkatanList = [22, 23, 24, 25];

        $firstNames = [
            'Ahmad',
            'Muhammad',
            'Rizky',
            'Dinda',
            'Putri',
            'Sari',
            'Nanda',
            'Eka',
            'Fajar',
            'Lestari',
            'Wulan',
            'Andi',
            'Ratna',
            'Bayu',
            'Indah',
            'Yudi',
            'Maya',
            'Rudi',
            'Fitri',
            'Agus',
            'Dian',
            'Hendra',
            'Siska',
            'Bambang',
            'Tari',
            'Joko',
            'Rina',
            'Arief',
            'Widya',
            'Doni',
            'Lina',
            'Hadi',
        ];

        $lastNames = [
            'Pratama',
            'Saputra',
            'Wibowo',
            'Kusuma',
            'Sari',
            'Putri',
            'Ningsih',
            'Lestari',
            'Wijaya',
            'Santoso',
            'Hidayat',
            'Rahman',
            'Fauzi',
            'Nugroho',
            'Setiawan',
            'Purnomo',
        ];

        $mahasiswaCount = 50;

        for ($i = 1; $i <= $mahasiswaCount; $i++) {
            // Generate NIM: E41251012
            // E (wajib) + 41 (wajib) + 22-25 (angkatan) + 4 digit random
            $angkatan = $angkatanList[array_rand($angkatanList)];
            $randomNum = str_pad($i, 4, '0', STR_PAD_LEFT); // 0001, 0002, dst
            $nim = 'E41' . $angkatan . $randomNum;

            // Generate Nama
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $nama = $firstName . ' ' . $lastName;

            // Generate Email: nim@student.polije.ac.id
            $email = strtolower($nim) . '@student.polije.ac.id';

            // Pick random golongan
            $golongan = $golongans->random();

            // Create Mahasiswa
            $mhsRecord = Mahasiswa::create([
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email,
                'angkatan' => 2000 + $angkatan, // 2022, 2023, 2024, 2025
                'semester_id' => $semester->id,
                'golongan_id' => $golongan->id,
                'tanggal_lahir' => fake()->date('Y-m-d', '2005-01-01'),
                'nik' => fake()->numerify('##############'),
                'no_hp' => fake()->phoneNumber(),
                'alamat' => fake()->address(),
            ]);

            // Create User for Mahasiswa
            User::create([
                'email' => $email,
                'password' => Hash::make('password'), // Default password
                'role' => 'mahasiswa',
                'mahasiswa_id' => $mhsRecord->id,
                'dosen_id' => null,
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);
        }

        $this->command->info("   Contoh: {$nim} (Angkatan {$angkatan}) → Semester {$mhsRecord->semester_aktif}");
        $this->command->info('✓ ' . $mahasiswaCount . ' mahasiswa created with default password: password');

    }
}
