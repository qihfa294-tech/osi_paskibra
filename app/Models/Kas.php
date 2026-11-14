<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $table = 'kas';

    protected $fillable = [
        'user_id',
        'nama_pembayar',
        'role_pembayar',
        'angkatan_pembayar',
        'nominal',
        'metode_pembayaran',
        'bukti',
        'bulan',
        'keterangan',
        'deskripsi',
        'tipe',
        'jenis',
        'status',
        'alasan_penolakan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}