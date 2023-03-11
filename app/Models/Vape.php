<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vape extends Model
{
    use HasFactory, SoftDeletes;
    // dari migtaration Vapesnya -> softDeletes

         protected $fillable = [
        'name', 'email', 'password',
        'address', 'houseNumber', 'phoneNumber','city',
        'roles'
    ];


    protected $guarded = [
        'id'
    ];

    // epoch untuk FE
    public function getCreatedAtAttribute($value) {
        // membuat assesor untuk mengakses file yang sudah ada
        return Carbon::parse($value)->timestamp;
    }

    // epoch untuk FE
    public function getUpdatedAtAttribute($value) {
        // membuat assesor untuk mengakses file yang sudah ada
        return Carbon::parse($value)->timestamp;
    }
}
