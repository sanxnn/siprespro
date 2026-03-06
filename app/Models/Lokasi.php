<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';

    protected $fillable = [
        'nama',
        'latitude',
        'longitude',
        'radius_meter',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius_meter' => 'integer',
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function isWithinRadius(float $lat, float $lng)
    {
        $distance = $this->calculateDistance($lat, $lng);
        return $distance <= $this->radius_meter;
    }

    private function calculateDistance(float $lat1, float $lng1)
    {
        $earthRadius = 6371000;
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad((float) $this->latitude);
        $lng2 = deg2rad((float) $this->longitude);

        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;

        $a = sin($dLat/2) * sin($dLat/2) +
             cos($lat1) * cos($lat2) *
             sin($dLng/2) * sin($dLng/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
}
