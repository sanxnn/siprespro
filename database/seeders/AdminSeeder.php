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
            [
                'email' => 'admin@polije.ac.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
            [
                'email' => 'admin.prodi@polije.ac.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
            [
                'email' => 'superadmin@polije.ac.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
        ];

        foreach ($admins as $admin) {
            User::create([
                'email' => $admin['email'],
                'password' => $admin['password'],
                'role' => $admin['role'],
                'mahasiswa_id' => null,
                'dosen_id' => null,
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);
        }

        $this->command->info('✓ 3 admin created with default password: admin123');
    }
}
