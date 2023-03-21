<?php

namespace App;

use App\Models\User;
use App\Models\Vape;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'vape_id', 'user_id', 'quantity',
        'total', 'status', 'payment_url',
    ];

        protected $guarded = [
            'id'
        ];

    // public function vape(){
    //     $this->hasOne(Vape::class, 'id', 'vape_id');
    // }

    // public function user(){
    //     $this->hasOne(User::class, 'id', 'user_id');
    // }

    public function vape(){
        return $this->belongsTo(Vape::class, 'vape_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

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
