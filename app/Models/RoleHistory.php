<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RoleHistory extends Model
{
    protected $fillable = ['user_id','from_role_id','to_role_id','assigned_by','notes'];
    public function user(){ return $this->belongsTo(User::class); }
}