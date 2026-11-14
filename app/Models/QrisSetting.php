<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QrisSetting extends Model
{
    protected $fillable = ['qris_image_path','merchant_name','is_active'];
}