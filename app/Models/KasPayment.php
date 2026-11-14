<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class KasPayment extends Model
{
    protected $fillable = ['user_id','minggu_ke','bulan','tahun','nominal_bayar','status','bukti_transfer','verified_by','verified_at'];
    public function user(){ return $this->belongsTo(User::class); }
}