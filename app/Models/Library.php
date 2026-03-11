<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'full_content',
        'type',
        'cover_image',
        'file_url',
        'other',
        'status',
    ];
    
}
