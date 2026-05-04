<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MahasiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Mahasiswa::with(['user', 'golongan', 'semester'])
            ->when(isset($this->filters['search']), function($q) {
                $search = $this->filters['search'];
                $q->where('nama', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%");
            })
            ->when(isset($this->filters['golongan']), fn($q) => $q->where('golongan_id', $this->filters['golongan']))
            ->when(isset($this->filters['angkatan']), fn($q) => $q->where('angkatan', $this->filters['angkatan']))
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIM', 'Nama Lengkap', 'Email', 'Golongan', 'Angkatan', 
            'Semester Sekarang', 'Tipe Sem.', 'No. HP', 'NIK', 'Alamat'
        ];
    }

    public function map($mhs): array
    {
        return [
            $mhs->nim,
            $mhs->nama,
            $mhs->user->email ?? $mhs->email,
            $mhs->golongan->nama ?? '-',
            $mhs->angkatan,
            $mhs->semester_aktif, // Memanggil Accessor di Model Lu
            $mhs->semester->nama ?? '-',
            $mhs->no_hp ?? '-',
            $mhs->nik ?? '-',
            $mhs->alamat ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2563EB']] // Warna Biru (Primary)
            ],
        ];
    }
}