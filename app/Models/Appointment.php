<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'appointment_end',
        'status',
        'other',
        'meeting_code',
        'type',
        'provider_subjective',
        'provider_objective',
        'provider_assessment',
        'provider_plan',
        'notes'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function review() {
        return $this->belongsTo(Review::class);
    }

    public function doctor() {
        return $this->belongsTo(Seller::class, 'doctor_id');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->other = (string) Str::uuid();
    //     });
    // }

}
