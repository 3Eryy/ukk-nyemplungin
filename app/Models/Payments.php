<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'payment_date',
        'amount',
        'payment_method',
        'payment_status',
    ];
}
