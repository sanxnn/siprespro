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
            $this->command->error('❌ Semester atau Golongan tidak ditemukan! Jalankan SemesterSeeder & GolonganSeeder dulu.');
            return;
        }

        // Sesuai request: Angkatan dikunci hanya di tahun 2025 (kode NIM: 25)
        $angkatanTahun = 2025;
        $angkatanKode = '25';

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
            'Hadi'
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
            'Purnomo'
        ];

        $mahasiswaCount = 50;

        for ($i = 1; $i <= $mahasiswaCount; $i++) {
            // Generate NIM: E41 + 25 + 4 digit urut (E41250001 sampai E41250050)
            $randomNum = str_pad($i, 4, '0', STR_PAD_LEFT);
            $nim = 'E41' . $angkatanKode . $randomNum;

            // Generate Nama (Gabungan acak depan + belakang)
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $nama = $firstName . ' ' . $lastName;

            // Generate Email: nim@student.polije.ac.id
            $email = strtolower($nim) . '@student.polije.ac.id';

            // Pilih golongan secara acak dari database
            $golongan = $golongans->random();

            // Create Mahasiswa
            $mhsRecord = Mahasiswa::create([
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email,
                'angkatan' => $angkatanTahun, // Tetap 2025
                'semester_id' => $semester->id, // Menggunakan semester aktif
                'golongan_id' => $golongan->id,
                'tanggal_lahir' => fake()->date('Y-m-d', '2007-01-01'), // Disesuaikan umur anak angkatan 2025
                'nik' => fake()->numerify('################'), // NIK Indonesia 16 digit
                'no_hp' => fake()->phoneNumber(),
                'alamat' => fake()->address(),
            ]);

            // Create User untuk Mahasiswa
            User::create([
                'email' => $email,
                'password' => Hash::make($nim), // Password default: nim
                'role' => 'mahasiswa',
                'mahasiswa_id' => $mhsRecord->id,
                'dosen_id' => null,
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);
        }

        // Tampilkan info sampel data terakhir yang berhasil dibuat di terminal
        $this->command->info("ℹ️ Contoh generate terakhir: {$nim} - {$nama} ({$golongan->nama})");
        $this->command->info('✓ ' . $mahasiswaCount . ' mahasiswa berhasil dibuat dengan password bawaan: password');
    }
}
