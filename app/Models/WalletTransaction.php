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

    public function user()
    {
        return $this->belongsTo(User::class, 'wallet_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Seller::class, 'wallet_id');
    }

    public function getOwnerAttribute()
    {
        if ($this->wallet_type === 'user') {
            return $this->belongsTo(User::class, 'wallet_id');
        }

        if ($this->wallet_type === 'doctor') {
            return $this->belongsTo(Seller::class, 'wallet_id');
        }

        return null;
    }
    
}
