<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    use HasFactory;

    protected $table = 'pertemuan';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'pertemuan_ke',
        'tanggal',
        'materi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'string',
        'pertemuan_ke' => 'integer',
    ];

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    public function scopeDibuka($query)
    {
        return $query->where('status', 'dibuka');
    }

    public function scopeDitutup($query)
    {
        return $query->where('status', 'ditutup');
    }

    public function isOpen()
    {
        return $this->status === 'dibuka';
    }

    public function getLabelAttribute()
    {
        $tanggal = \Carbon\Carbon::parse($this->tanggal)->format('d M Y');
        return "Pertemuan {$this->pertemuan_ke} - {$tanggal}";
    }
}
