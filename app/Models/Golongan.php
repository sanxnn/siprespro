<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;

    protected $table = 'golongan';

    protected $fillable = [
        'nama',
        'semester_id',
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function kelasPerkuliahan()
    {
        return $this->belongsToMany(
            KelasPerkuliahan::class, 
            'kelas_golongan', 
            'golongan_id', 
            'kelas_perkuliahan_id'
        );
    }
}
