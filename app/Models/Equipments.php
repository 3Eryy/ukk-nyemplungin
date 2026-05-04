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

    public function category()
    {
        return $this->belongsTo(EquipmentCategories::class, 'category_id');
    }

    public function rentalItems()
    {
        return $this->hasMany(RentalItems::class, 'equipment_id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'equipment_id');
    }
}
