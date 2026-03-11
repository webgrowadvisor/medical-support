<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Seller extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'status',
        'api_key',
        'gst',
        'address',
        'logo',
        'specialization',
        'slug',
        'gst_image',
    ];

    protected $hidden = [
        'password', 'remember_token', 'email', 'mobile', 'gst', 'address', 'logo'
    ];

    public function wallet()
    {
        return $this->hasOne(DoctorWallet::class, 'doctor_id');
    }
    
}
