<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'seat_id',
        'price',
        'is_reserved',
        'seat_type'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_reserved' => 'boolean',
    ];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }
}