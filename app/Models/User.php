<?php
namespace App\Models;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasFactory, Notifiable, CanResetPassword, HasRoles;
    protected $fillable = [
        'email',
        'password',
        'role',
        'mahasiswa_id',
        'dosen_id',
        'is_active',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'role' => 'string',
        ];
    }
    public function getDashboardRoute()
    {
        return match ($this->role) {
            'admin' => 'admin.dashboard',
            'dosen' => 'dosen.dashboard',
            'mahasiswa' => 'mahasiswa.dashboard',
            default => 'dashboard'
        };
    }
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id', 'mahasiswa_id');
    }
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'id', 'dosen_id');
    }
    public function getRoleLabelAttribute()
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'dosen' => 'Dosen',
            'mahasiswa' => 'Mahasiswa',
            default => 'Unknown',
        };
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isDosen()
    {
        return $this->role === 'dosen';
    }
    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }
}
