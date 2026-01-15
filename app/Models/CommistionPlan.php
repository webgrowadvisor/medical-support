<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommistionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialization',
        'doctor_id',
        'type',
        'commission_value',
        'status'
    ];

    public function doctor() {
        return $this->belongsTo(Seller::class, 'doctor_id');
    }
    
}
