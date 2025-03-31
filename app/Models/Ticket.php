<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'show_seat_id',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function showSeat()
    {
        return $this->belongsTo(ShowSeat::class);
    }
}