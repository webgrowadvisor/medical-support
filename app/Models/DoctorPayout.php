<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'amount',
        'status',
        'transaction_id',
        'admin_note'
    ];

    public function doctor()
    {
        return $this->belongsTo(Seller::class, 'doctor_id');
    }

}
