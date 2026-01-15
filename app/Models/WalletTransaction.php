<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_type',
        'wallet_id',
        'type',
        'amount',
        'balance',
        'reason',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array'
    ];
    
}
