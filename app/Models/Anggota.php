<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model {
    protected $fillable = [
        'nama','nama_lengkap','jabatan','kategori','angkatan','foto','user_id','status',
    ];

    // RELASI 1 AJA YANG DIPAKE - yang user_id
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isAlumni(): bool
    {
        return $this->status === 'alumni' || $this->status === 'Alumni';
    }

    public function scopeAktif($query)
    {
        return $query->where(function($q){
            $q->where('status','aktif')
              ->orWhere('status','Aktif')
              ->orWhere('status','active')
              ->orWhereNull('status');
        });
    }

    public function scopeAlumni($query)
    {
        return $query->where('status','alumni');
    }
    
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', strtoupper($kategori));
    }
}