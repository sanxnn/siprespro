<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasis = [
            [
                'nama' => 'Gedung Production Pertanian (PP)',
                'latitude' => -8.15783300,
                'longitude' => 113.72252800,
                'radius_meter' => 50,
            ],
            [
                'nama' => 'Lingkungan Kampus Polije',
                'latitude' => -8.15848200,
                'longitude' => 113.72082200,
                'radius_meter' => 500,
            ],
            [
                'nama' => 'Umum / Dimana Saja',
                'latitude' => -8.15848200,
                'longitude' => 113.72082200,
                'radius_meter' => 100000,
            ]
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::create($lokasi);
        }

        $this->command->info('✓ ' . count($lokasis) . ' data lokasi presensi berhasil dibuat.');
    }
}
