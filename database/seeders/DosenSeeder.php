<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosens = [
            ['nip' => '198501012010011001', 'nidn' => '0701018501', 'nama' => 'Bety Kristianti', 'jabatan' => 'Lektor', 'email' => 'bety@polije.ac.id'],
            ['nip' => '198703022011011002', 'nidn' => '0702038701', 'nama' => 'Irwan Setiawan', 'jabatan' => 'Lektor', 'email' => 'irwan@polije.ac.id'],
            ['nip' => '199005032015041003', 'nidn' => '0703059001', 'nama' => 'Rini Agustina', 'jabatan' => 'Asisten Ahli', 'email' => 'rini@polije.ac.id'],
            ['nip' => '198807042014041004', 'nidn' => '0704078801', 'nama' => 'Agus Santoso', 'jabatan' => 'Lektor', 'email' => 'agus@polije.ac.id'],
            ['nip' => '199209052018041005', 'nidn' => '0705099201', 'nama' => 'Dwi Putri Handayani', 'jabatan' => 'Asisten Ahli', 'email' => 'dwi@polije.ac.id'],
            ['nip' => '198611062012041006', 'nidn' => '0706118601', 'nama' => 'Hariyanto', 'jabatan' => 'Lektor Kepala', 'email' => 'hariyanto@polije.ac.id'],
            ['nip' => '199112072017041007', 'nidn' => '0707129101', 'nama' => 'Siti Nurhaliza', 'jabatan' => 'Asisten Ahli', 'email' => 'siti@polije.ac.id'],
            ['nip' => '198908082013041008', 'nidn' => '0708088901', 'nama' => 'Budi Prasetyo', 'jabatan' => 'Lektor', 'email' => 'budi@polije.ac.id'],
        ];

        foreach ($dosens as $dosen) {
            // Create Dosen
            $dosenRecord = Dosen::create([
                'nip' => $dosen['nip'],
                'nidn' => $dosen['nidn'],
                'nama' => $dosen['nama'],
                'jabatan' => $dosen['jabatan'],
                'email' => $dosen['email'],
                'tanggal_lahir' => fake()->date('Y-m-d', '2000-01-01'),
                'nik' => fake()->numerify('##############'),
                'no_hp' => fake()->phoneNumber(),
                'alamat' => fake()->address(),
            ]);

            // Create User for Dosen
            User::create([
                'email' => $dosen['email'],
                'password' => Hash::make('dosen123'), // Default password
                'role' => 'dosen',
                'dosen_id' => $dosenRecord->id,
                'mahasiswa_id' => null,
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);
        }

        $this->command->info('✓ ' . count($dosens) . ' dosen created with default password: dosen123');

    }
}
