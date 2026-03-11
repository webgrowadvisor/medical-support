<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id', 'type', 'title', 'note', 'is_read'
    ];

    public function doctor()
    {
        return $this->belongsTo(Seller::class, 'doctor_id');
    }
    
}
