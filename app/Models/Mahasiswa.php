<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'angkatan',
        'semester_id',
        'golongan_id',
        'tanggal_lahir',
        'nik',
        'no_hp',
        'alamat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'angkatan' => 'integer',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'mahasiswa_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    public function getNamaLengkapAttribute()
    {
        return "{$this->nama} ({$this->nim})";
    }

    public function getSemesterAktifAttribute()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        $angkatanYear = $this->angkatan;

        $yearDiff = $currentYear - $angkatanYear;

        if ($currentMonth >= 8) {
            $semester = ($yearDiff * 2) + 1;
        }
        else {
            $semester = $yearDiff * 2;
        }

        return min($semester, 8);
    }

    public function getSemesterLabelAttribute(): string
    {
        $semester = $this->semester_aktif;

        $ganjilGenap = (date('n') >= 8) ? 'Ganjil' : 'Genap';

        return "Semester {$semester} ({$ganjilGenap})";
    }

    public function isSemesterAktif(): bool
    {
        return $this->semester_aktif <= 8;
    }
}
