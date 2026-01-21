<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Returns extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'return_date',
        'condition',
        'fine_amount',
        'notes',
        'handled_by',
    ];
}
