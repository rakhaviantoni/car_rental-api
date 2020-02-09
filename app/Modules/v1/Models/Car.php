<?php
namespace App\Modules\v1\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

Class Car extends Model 
{
    use SoftDeletes;
    protected $fillable = ['registration_no', 'color', 'created_at', 'updated_at'];
}
