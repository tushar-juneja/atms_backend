<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'purchase_date',
        'show_discount_id',
        'original_amount',
        'final_amount',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'original_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showDiscount()
    {
        return $this->belongsTo(ShowDiscount::class);
    }
}