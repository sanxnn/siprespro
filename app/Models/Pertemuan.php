<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pertemuan extends Model
{
    use HasFactory;

    protected $table = 'pertemuan';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'pertemuan_ke',
        'tanggal',
        'jam_mulai',   // Tambah ini
        'jam_selesai', // Tambah ini
        'lokasi_id',   // Tambah ini
        'materi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'string',
        'pertemuan_ke' => 'integer',
        // Jam biarkan string saja agar mudah diolah substr atau Carbon
    ];

    // RELASI
    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class);
    }

    public function lokasi() // Tambah relasi ke Lokasi
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    // SCOPES
    public function scopeDibuka($query)
    {
        return $query->where('status', 'dibuka');
    }

    public function scopeDitutup($query)
    {
        return $query->where('status', 'ditutup');
    }

    // HELPER METHODS
    public function isOpen()
    {
        return $this->status === 'dibuka';
    }

    public function getLabelAttribute()
    {
        $tanggal = Carbon::parse($this->tanggal)->format('d M Y');
        return "Pertemuan {$this->pertemuan_ke} - {$tanggal}";
    }

    // LOGIC STATUS ABSENSI
    public function getStatusAbsensiAttribute()
    {
        $sekarang = now();
        $hariIni = $sekarang->format('Y-m-d');
        $jamSekarang = $sekarang->format('H:i:s');

        // Gunakan format Y-m-d untuk perbandingan tanggal yang akurat
        $tanggalPertemuan = Carbon::parse($this->tanggal)->format('Y-m-d');

        // 1. Cek Tanggal
        if ($tanggalPertemuan < $hariIni)
            return 'Selesai';
        if ($tanggalPertemuan > $hariIni)
            return 'Belum Mulai';

        // 2. Jika Tanggalnya Hari Ini, Cek Jam
        if ($jamSekarang < $this->jam_mulai)
            return 'Belum Mulai';
        if ($jamSekarang > $this->jam_selesai)
            return 'Terlambat/Selesai';

        // 3. Jika Berada di Dalam Range Jam dan Status Manualnya 'dibuka'
        if ($this->status === 'dibuka') {
            return 'Aktif';
        }

        return 'Ditutup (Manual)';
    }
}