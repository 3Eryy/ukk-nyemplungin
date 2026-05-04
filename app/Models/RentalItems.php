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
        'price',
        'subtotal',
        'rental_start',
        'rental_end',
    ];

    public function rental()
    {
        return $this->belongsTo(Rentals::class, 'rental_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipments::class, 'equipment_id');
    }
}
