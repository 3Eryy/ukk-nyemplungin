<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rentals extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rental_start',
        'rental_end',
        'total_price',
        'status',
        'approved_by',
    ];
}
