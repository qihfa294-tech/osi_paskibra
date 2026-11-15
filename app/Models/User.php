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
        // Biodata - WAJIB ADA, kalau tidak ada $user->update() akan diam-diam gagal
        'nama_lengkap',
        'nama_panggilan',
        'jurusan',
        'kelas',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'agama',
        'jenis_kelamin',
        'foto',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'must_change_password' => 'boolean',
        'tanggal_lahir' => 'date',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function profile()
    {
        return $this->hasOne(MemberProfile::class);
    }

    public function roleHistories()
    {
        return $this->hasMany(RoleHistory::class);
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    // INI PENYEBAB PP GAK KEGANTI:
    // Accessor lama cuma baca $this->anggota?->foto.
    // Kalau anggota_id NULL / relasi gagal, PP selalu NULL -> balik ke huruf M.
    // Sekarang: utamakan users.foto, fallback ke anggota.foto.
    public function getFotoAttribute()
    {
        return $this->attributes['foto'] ?? $this->anggota?->foto ?? null;
    }

    public function getNamaLengkapAttribute()
    {
        return $this->attributes['nama_lengkap'] ?? $this->anggota?->nama_lengkap ?? $this->anggota?->nama ?? $this->attributes['name'] ?? $this->username ?? null;
    }

    public function getNamaPanggilanAttribute()
    {
        return $this->attributes['nama_panggilan'] ?? $this->anggota?->nama_panggilan ?? null;
    }

    public function getJurusanAttribute()
    {
        return $this->attributes['jurusan'] ?? $this->anggota?->jurusan ?? null;
    }

    public function getKelasAttribute()
    {
        return $this->attributes['kelas'] ?? $this->anggota?->kelas ?? null;
    }

    public function getAlamatAttribute()
    {
        return $this->attributes['alamat'] ?? $this->anggota?->alamat ?? null;
    }

    public function getTempatLahirAttribute()
    {
        return $this->attributes['tempat_lahir'] ?? $this->anggota?->tempat_lahir ?? null;
    }

    public function getTanggalLahirAttribute()
    {
        return $this->attributes['tanggal_lahir'] ?? $this->anggota?->tanggal_lahir ?? null;
    }

    public function getNoHpAttribute()
    {
        return $this->attributes['no_hp'] ?? $this->anggota?->no_hp ?? null;
    }

    public function getAgamaAttribute()
    {
        return $this->attributes['agama'] ?? $this->anggota?->agama ?? null;
    }

    public function getJenisKelaminAttribute()
    {
        return $this->attributes['jenis_kelamin'] ?? $this->anggota?->jenis_kelamin ?? null;
    }

    public function isDeveloper(): bool
    {
        return $this->role_id == 1;
    }

    public function isAlumni(): bool
    {
        $statusAnggota = $this->anggota?->status ?? $this->status;
        return $statusAnggota === 'Lulus' || $this->role_id == 4;
    }

    public function isActive(): bool
    {
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
        return !$this->isAlumni() && !$this->isNonAktif();
    }
}