<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalItems extends Model
{
    use HasFactory;
    protected $fillable = [
        'rental_id',
        'equipment_id',
        'quantity',
    ];
}
