<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $roleFilter;

    public function __construct($roleFilter = null)
    {
        $this->roleFilter = $roleFilter;
    }

    public function collection()
    {
        return User::with(['mahasiswa', 'dosen'])
                   ->whereNotIn('role', ['admin'])
                   ->when($this->roleFilter, fn($q) => $q->where('role', $this->roleFilter))
                   ->get();
    }

    public function headings(): array
    {
        return [
            'Nama', 'Email', 'Role', 'NIM/NIP', 'Angkatan/Jabatan', 
            'Semester', 'No. HP', 'Alamat', 'Status', 'Bergabung'
        ];
    }

    public function map($user): array
    {
        return [
            $user->mahasiswa->nama ?? $user->dosen->nama ?? $user->email,
            $user->email,
            ucfirst($user->role),
            $user->mahasiswa->nim ?? $user->dosen->nip ?? '-',
            $user->mahasiswa->angkatan ?? $user->dosen->jabatan ?? '-',
            $user->mahasiswa->semester_id ?? '-',
            $user->mahasiswa->no_hp ?? $user->dosen->no_hp ?? '-',
            $user->mahasiswa->alamat ?? $user->dosen->alamat ?? '-',
            $user->is_active ? 'Aktif' : 'Nonaktif',
            \Carbon\Carbon::parse($user->created_at)->format('d M Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '059669']]],
        ];
    }
}