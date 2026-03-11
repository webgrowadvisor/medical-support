<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'otp',
        'expires_at',
        'is_used'
    ];

}
