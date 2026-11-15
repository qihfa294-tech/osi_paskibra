<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $fillable = [
        'nama',
        'nama_lengkap',
        'nama_panggilan',
        'jabatan',
        'kategori',
        'angkatan',
        'foto',
        'user_id',
        'status',
        'nis',
        'jurusan',
        'kelas',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'alamat',
        'agama',
        'asal_sekolah',
        'golongan_darah',
        'ukuran_baju',
        'biodata_lengkap',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'biodata_lengkap' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isAlumni(): bool
    {
        return in_array($this->status, ['alumni', 'Alumni', 'Lulus', 'lulus']);
    }

    public function scopeAktif($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'aktif')
                ->orWhere('status', 'Aktif')
                ->orWhere('status', 'active')
                ->orWhereNull('status');
        });
    }

    public function scopeAlumni($query)
    {
        return $query->whereIn('status', ['alumni', 'Alumni', 'Lulus']);
    }

    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', strtoupper($kategori));
    }
}