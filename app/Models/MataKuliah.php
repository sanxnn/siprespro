<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode_mk',
        'nama',
        'sks',
        'semester_id',
    ];

    protected $casts = [
        'sks' => 'integer',
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function kelasPerkuliahan()
    {
        return $this->hasMany(KelasPerkuliahan::class);
    }
}
