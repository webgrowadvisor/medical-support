<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'user_id',
        'rating',
        'review',
        'review_rection'
    ];

    public function doctor() {
        return $this->belongsTo(Seller::class, 'doctor_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }

}
