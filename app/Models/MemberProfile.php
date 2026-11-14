<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MemberProfile extends Model
{
    protected $fillable = ['user_id','nisn','kelas','tempat_lahir','tanggal_lahir','no_telp','no_telp_ortu','alamat_lengkap','nama_orang_tua','foto_profil','privacy_settings','bio'];
    protected $casts = ['privacy_settings' => 'array', 'tanggal_lahir' => 'date'];
    public function user(){ return $this->belongsTo(User::class); }
}