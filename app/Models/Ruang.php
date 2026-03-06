<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $table = 'ruang';

    protected $fillable = [
        'nama',
        'kapasitas',
        'gedung',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
    ];

    public function kelasPerkuliahan()
    {
        return $this->hasMany(KelasPerkuliahan::class);
    }
}
