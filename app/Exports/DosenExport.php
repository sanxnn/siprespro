<?php

namespace App\Exports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DosenExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Dosen::with(['user'])
            ->when(isset($this->filters['search']), function($q) {
                $search = $this->filters['search'];
                $q->where('nama', 'like', "%{$search}%")->orWhere('nip', 'like', "%{$search}%");
            })
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIP', 'Nama Dosen', 'Email', 'No. HP', 'Alamat', 'Status Akun'
        ];
    }

    public function map($dosen): array
    {
        return [
            $dosen->nip ?? '-',
            $dosen->nama,
            $dosen->user->email ?? '-',
            $dosen->no_hp ?? '-',
            $dosen->alamat ?? '-',
            ($dosen->user->is_active ?? false) ? 'Aktif' : 'Nonaktif',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']] // Warna Indigo
            ],
        ];
    }
}