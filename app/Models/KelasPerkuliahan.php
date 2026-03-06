<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasPerkuliahan extends Model
{
    use HasFactory;

    protected $table = 'kelas_perkuliahan';

    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'ruang_id',
        'nama_kelas',
        'tipe_kelas',
    ];

    protected $casts = [
        'tipe_kelas' => 'string',
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    public function golongans()
    {
        return $this->belongsToMany(
            Golongan::class, 
            'kelas_golongan', 
            'kelas_perkuliahan_id', 
            'golongan_id'
        );
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function pertemuans()
    {
        return $this->hasMany(Pertemuan::class)->orderBy('pertemuan_ke');
    }

    public function getNamaLengkapAttribute()
    {
        return "{$this->mataKuliah->kode_mk} - {$this->nama_kelas}";
    }
}
