<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'discount_amount',
        'minimum_cart_value',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'minimum_cart_value' => 'decimal:2',
    ];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }
}