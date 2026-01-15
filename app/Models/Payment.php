<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'appointment_id',
        'payment_type',
        'amount',
        'commission',
        'service_charge',
        'net_amount',
        'payment_status',
        'payment_method',
        'transaction_id'
    ];
    
}
