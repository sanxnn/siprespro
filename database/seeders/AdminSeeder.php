<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            ['email' => 'admin@polije.ac.id'],
            ['email' => 'admin.prodi@polije.ac.id'],
            ['email' => 'superadmin@polije.ac.id'],
        ];

        foreach ($admins as $admin) {
            User::create([
                'email' => $admin['email'],
                'password' => Hash::make('admin123'), // Di-hash langsung di sini agar array di atas lebih bersih
                'role' => 'admin',
                'mahasiswa_id' => null,
                'dosen_id' => null,
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);
        }

        $this->command->info('✓ ' . count($admins) . ' admin created with default password: admin123');
    }
}
