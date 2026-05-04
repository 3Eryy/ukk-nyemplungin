<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function equipments()
    {
        return $this->hasMany(Equipments::class, 'category_id');
    }
}
