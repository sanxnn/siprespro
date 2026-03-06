<?php

namespace Database\Seeders;

use App\Models\Golongan;
use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $semester = Semester::where('status', 'aktif')->first();

        if (!$semester) {
            $this->command->error('Semester aktif tidak ditemukan! Jalankan SemesterSeeder dulu.');
            return;
        }

        $golongans = [
            ['nama' => 'Golongan A'],
            ['nama' => 'Golongan B'],
            ['nama' => 'Golongan C'],
            ['nama' => 'Golongan D'],
        ];

        foreach ($golongans as $golongan) {
            Golongan::create([
                'nama' => $golongan['nama'],
                'semester_id' => $semester->id,
            ]);
        }
    }
}
