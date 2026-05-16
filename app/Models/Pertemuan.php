<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Pertemuan extends Model
{
    use HasFactory;
    protected $table = 'pertemuan';
    protected $fillable = [
        'kelas_perkuliahan_id',
        'pertemuan_ke',
        'tanggal',
        'jam_mulai',   
        'jam_selesai', 
        'lokasi_id',   
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
    public function lokasi() 
    {
        return $this->belongsTo(Lokasi::class);
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
        $tanggal = Carbon::parse($this->tanggal)->format('d M Y');
        return "Pertemuan {$this->pertemuan_ke} - {$tanggal}";
    }
    public function getStatusAbsensiAttribute()
    {
        $sekarang = now();
        $hariIni = $sekarang->format('Y-m-d');
        $jamSekarang = $sekarang->format('H:i:s');
        $tanggalPertemuan = Carbon::parse($this->tanggal)->format('Y-m-d');
        if ($tanggalPertemuan < $hariIni)
            return 'Selesai';
        if ($tanggalPertemuan > $hariIni)
            return 'Belum Mulai';
        if ($jamSekarang < $this->jam_mulai)
            return 'Belum Mulai';
        if ($jamSekarang > $this->jam_selesai)
            return 'Terlambat/Selesai';
        if ($this->status === 'dibuka') {
            return 'Aktif';
        }
        return 'Ditutup (Manual)';
    }
}