<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'lokasi_id',
    ];

    protected $casts = [
        'hari' => 'string',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function getWaktuAttribute()
    {
        return "{$this->jam_mulai?->format('H:i')} - {$this->jam_selesai?->format('H:i')}";
    }

    public function getHariLabelAttribute()
    {
        return match ($this->hari) {
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            default => ucfirst($this->hari),
        };
    }
}
