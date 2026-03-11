<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_type',
        'actor_id',
        'event',
        'ip_address',
        'user_agent',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array'
    ];
    

}
