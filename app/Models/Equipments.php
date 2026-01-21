<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipments extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'hourly_price',
        'stock',
        'condition_status',
        'available_status',
        'image',
    ];
}
