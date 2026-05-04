<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'equipment_id',
        'quantity',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipments::class, 'equipment_id');
    }
}
