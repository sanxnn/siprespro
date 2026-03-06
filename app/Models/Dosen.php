<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'nip',
        'nidn',
        'nama',
        'email',
        'jabatan',
        'tanggal_lahir',
        'nik',
        'no_hp',
        'alamat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'dosen_id');
    }

    public function kelasPerkuliahan()
    {
        return $this->hasMany(KelasPerkuliahan::class);
    }

    public function getNamaLengkapAttribute()
    {
        return $this->jabatan ? "{$this->jabatan} {$this->nama}" : $this->nama;
    }

}
