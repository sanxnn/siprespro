<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'pertemuan_id',
        'mahasiswa_id',
        'waktu_presensi',
        'status',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'waktu_presensi' => 'datetime',
        'status' => 'string',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'hadir' => 'success',
            'izin' => 'warning',
            'sakit' => 'info',
            'alpha' => 'danger',
            default => 'secondary',
        };
    }
}
