<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->command->info('======================================================');
        $this->command->info('      MEMULAI PROSES SEEDING DATABASE UTAMA          ');
        $this->command->info('======================================================');

        $this->command->warn('▶ Menjalankan Master Data Mandiri...');
        $this->call(SemesterSeeder::class);
        $this->call(RuangSeeder::class);
        $this->call(LokasiSeeder::class);
        $this->call(DosenSeeder::class);
        $this->call(AdminSeeder::class);
        $this->command->info('------------------------------------------------------');

        $this->command->warn('▶ Menjalankan Master Data Berelasi Level 1...');
        $this->call(GolonganSeeder::class);
        $this->call(MataKuliahSeeder::class);
        $this->command->info('------------------------------------------------------');

        $this->command->warn('▶ Menjalankan Data Mahasiswa dan Akun Relasi...');
        $this->call(MahasiswaSeeder::class);
        
        $this->command->info('======================================================');
        $this->command->info('🎉 SEMUA DATA SEEDER BERHASIL DISUNTIKKAN KE DATABASE 🎉');
        $this->command->info('======================================================');

    }
}
