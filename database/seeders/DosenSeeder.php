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
            ['nip' => '198501012010011001', 'nidn' => '0701018501', 'nama' => 'Bety Kristianti', 'jabatan' => 'Lektor'],
            ['nip' => '198703022011011002', 'nidn' => '0702038701', 'nama' => 'Irwan Setiawan', 'jabatan' => 'Lektor'],
            ['nip' => '199005032015041003', 'nidn' => '0703059001', 'nama' => 'Rini Agustina', 'jabatan' => 'Asisten Ahli'],
            ['nip' => '198807042014041004', 'nidn' => '0704078801', 'nama' => 'Agus Santoso', 'jabatan' => 'Lektor'],
            ['nip' => '199209052018041005', 'nidn' => '0705099201', 'nama' => 'Dwi Putri Handayani', 'jabatan' => 'Asisten Ahli'],
            ['nip' => '198611062012041006', 'nidn' => '0706118601', 'nama' => 'Hariyanto', 'jabatan' => 'Lektor Kepala'],
            ['nip' => '199112072017041007', 'nidn' => '0707129101', 'nama' => 'Siti Nurhaliza', 'jabatan' => 'Asisten Ahli'],
            ['nip' => '198908082013041008', 'nidn' => '0708088901', 'nama' => 'Budi Prasetyo', 'jabatan' => 'Lektor'],
            ['nip' => '199304102019031009', 'nidn' => '0710049301', 'nama' => 'Eko Prasetyo', 'jabatan' => 'Asisten Ahli'],
            ['nip' => '198402122009022010', 'nidn' => '0712028402', 'nama' => 'Fitriani Handayani', 'jabatan' => 'Lektor Kepala'],
        ];

        foreach ($dosens as $dosen) {
            $namaDepan = Str::lower(explode(' ', $dosen['nama'])[0]);
            $emailDosen = $namaDepan . '@polije.ac.id';

            $dosenRecord = Dosen::create([
                'nip' => $dosen['nip'],
                'nidn' => $dosen['nidn'],
                'nama' => $dosen['nama'],
                'jabatan' => $dosen['jabatan'],
                'email' => $emailDosen,
                'tanggal_lahir' => fake()->date('Y-m-d', '2000-01-01'),
                'nik' => fake()->numerify('################'),
                'no_hp' => fake()->phoneNumber(),
                'alamat' => fake()->address(),
            ]);

            User::create([
                'email' => $emailDosen,
                'password' => Hash::make('dosen123'), // Default password
                'role' => 'dosen',
                'dosen_id' => $dosenRecord->id,
                'mahasiswa_id' => null,
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);
        }

        $this->command->info('✓ ' . count($dosens) . ' dosen berhasil dibuat dengan password bawaan: dosen123');
    }
}
