<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $semesters = [
            ['nama' => 'Genap 2025/2026', 'tahun_ajaran' => '2025/2026', 'status' => 'aktif'],
            ['nama' => 'Ganjil 2025/2026', 'tahun_ajaran' => '2025/2026', 'status' => 'nonaktif'],
            ['nama' => 'Genap 2024/2025', 'tahun_ajaran' => '2024/2025', 'status' => 'nonaktif'],
            ['nama' => 'Ganjil 2024/2025', 'tahun_ajaran' => '2024/2025', 'status' => 'nonaktif'],
            ['nama' => 'Genap 2023/2024', 'tahun_ajaran' => '2023/2024', 'status' => 'nonaktif'],
            ['nama' => 'Ganjil 2023/2024', 'tahun_ajaran' => '2023/2024', 'status' => 'nonaktif'],
            ['nama' => 'Genap 2022/2023', 'tahun_ajaran' => '2022/2023', 'status' => 'nonaktif'],
            ['nama' => 'Ganjil 2022/2023', 'tahun_ajaran' => '2022/2023', 'status' => 'nonaktif'],
        ];

        foreach ($semesters as $semester) {
            Semester::create($semester);
        }
    }
}
