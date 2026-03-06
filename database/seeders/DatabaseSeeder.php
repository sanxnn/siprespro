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
        $this->call([
            SemesterSeeder::class,
            GolonganSeeder::class,
            AdminSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,
        ]);

        $this->command->info('✅ All seeders completed successfully!');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('📋 LOGIN CREDENTIALS:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('🔐 ADMIN:');
        $this->command->info('   Email: admin@polije.ac.id');
        $this->command->info('   Password: admin123');
        $this->command->info('');
        $this->command->info('👨‍🏫 DOSEN:');
        $this->command->info('   Email: bety@polije.ac.id');
        $this->command->info('   Password: dosen123');
        $this->command->info('');
        $this->command->info('👨‍🎓 MAHASISWA:');
        $this->command->info('   Email: e41220001@student.polije.ac.id');
        $this->command->info('   Password: mhs123');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
