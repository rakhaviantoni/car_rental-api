<?php
namespace App\Modules\v1\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

Class Reservation extends Model 
{
    use SoftDeletes;
    protected $fillable = ['resitration_no', 'customer', 'date', 'created_at', 'updated_at'];
}
