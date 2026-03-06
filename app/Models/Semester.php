<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semester';

    protected $fillable = [
        'nama',
        'tahun_ajaran',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function golongans()
    {
        return $this->hasMany(Golongan::class);
    }

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function mataKuliahs()
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeNonaktif($query)
    {
        return $query->where('status', 'nonaktif');
    }
}
