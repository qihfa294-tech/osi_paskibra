<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
        'angkatan_masuk',
        'jabatan',
        'status',
        'must_change_password',
        'anggota_id',
    ];
    
    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'must_change_password' => 'boolean',
    ];

    // RELASI LAMA LU - GW PERTAHANIN SEMUA
    public function role(){ return $this->belongsTo(Role::class); }
    public function profile(){ return $this->hasOne(MemberProfile::class); }
    public function roleHistories(){ return $this->hasMany(RoleHistory::class); }

    // RELASI BARU KE ANGGOTA
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function isDeveloper(): bool
    {
        return $this->role_id == 1;
    }

    public function isAlumni(): bool
    {
        // FIX: di DB lu status alumni itu "Lulus", bukan "alumni"
        // Cek dari Anggota juga biar aman, soalnya status beneran disimpen di sana
        $statusAnggota = $this->anggota?->status ?? $this->status;
        return $statusAnggota === 'Lulus' || $this->role_id == 4;
    }

    public function isActive(): bool
    {
        // FIX: di DB lu "Aktif", bukan "active"
        $statusAnggota = $this->anggota?->status ?? $this->status;
        return $statusAnggota === 'Aktif';
    }

    public function isNonAktif(): bool
    {
        $statusAnggota = $this->anggota?->status ?? $this->status;
        return $statusAnggota === 'Non-Aktif';
    }

    public function canWrite(): bool
    {
        // Alumni + Non-Aktif cuma view, gak bisa POST/DELETE
        return !$this->isAlumni() && !$this->isNonAktif();
    }
}