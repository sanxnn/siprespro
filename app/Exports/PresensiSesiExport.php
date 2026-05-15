<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PresensiSesiExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected $pertemuanId;
    protected $rowNumber = 0;

    public function __construct($pertemuanId)
    {
        $this->pertemuanId = $pertemuanId;
    }

    /**
     * Ambil data mahasiswa beserta status presensinya
     */
    public function collection()
    {
        $pertemuan = DB::table('pertemuan')->where('id', $this->pertemuanId)->first();

        if (!$pertemuan) {
            return collect([]);
        }

        // Tarik semua mahasiswa yang satu golongan dengan kelas perkuliahan ini
        return DB::table('mahasiswa')
            ->join('kelas_golongan', 'mahasiswa.golongan_id', '=', 'kelas_golongan.golongan_id')
            ->leftJoin('presensi', function ($join) {
                $join->on('mahasiswa.id', '=', 'presensi.mahasiswa_id')
                    ->where('presensi.pertemuan_id', '=', $this->pertemuanId);
            })
            ->where('kelas_golongan.kelas_perkuliahan_id', $pertemuan->kelas_perkuliahan_id)
            ->select(
                'mahasiswa.nim',
                'mahasiswa.nama',
                'presensi.waktu_presensi',
                'presensi.status',
                'presensi.latitude',
                'presensi.longitude'
            )
            ->orderBy('mahasiswa.nim', 'asc')
            ->get();
    }

    /**
     * Struktur baris Excel
     */
    public function map($mhs): array
    {
        $this->rowNumber++;

        // Logic penentuan waktu & status jancok
        $waktu = $mhs->waktu_presensi ? date('H:i:s', strtotime($mhs->waktu_presensi)) . ' WIB' : 'Belum Mengisi';
        $status = $mhs->status ? strtoupper($mhs->status) : 'ALFA';
        $gps = ($mhs->latitude && $mhs->longitude) ? $mhs->latitude . ', ' . $mhs->longitude : '-';

        return [
            $this->rowNumber,
            $mhs->nim,
            $mhs->nama,
            $waktu,
            $status,
            $gps
        ];
    }

    /**
     * Header Tabel Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'NIM',
            'Nama Mahasiswa',
            'Waktu Absen',
            'Status Kehadiran',
            'Koordinat (GPS)'
        ];
    }

    /**
     * Nama Sheet
     */
    public function title(): string
    {
        return 'Sesi Presensi';
    }
}