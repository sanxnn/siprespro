<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Lokasi::insert([
            [
                'nama' => 'Gedung Produksi Pertanian (PP)',
                'latitude' => -8.15783300,
                'longitude' => 113.72252800,
                'radius_meter' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Lingkungan Kampus Polije',
                'latitude' => -8.15848200,
                'longitude' => 113.72082200,
                'radius_meter' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Umum / Dimana Saja',
                'latitude' => -8.15848200,
                'longitude' => 113.72082200,
                'radius_meter' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
