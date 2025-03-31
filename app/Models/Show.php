<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date_time',
        'artist',
        'published',
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'published' => 'boolean',
    ];
}