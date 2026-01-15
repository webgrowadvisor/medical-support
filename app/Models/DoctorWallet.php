<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorWallet extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'balance'];

    public function doctor() {
        return $this->belongsTo(Seller::class, 'doctor_id');
    }

    public function transactions() {
        return $this->hasMany(WalletTransaction::class, 'wallet_id')
                    ->where('wallet_type', 'doctor');
    }
    
}
