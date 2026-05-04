<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Rentals extends Model
{
    use LogsActivity, HasFactory;

    protected $fillable = [
        'user_id',
        'rental_start',
        'rental_end',
        'total_price',
        'status',
        'approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rentalItems()
    {
        return $this->hasMany(RentalItems::class, 'rental_id');
    }

    public function items()
    {
        return $this->belongsTo(Equipments::class, 'equipment_id');
    }

    public function return()
    {
        return $this->hasOne(Returns::class, 'rental_id');
    }

    // Rentals.php
    public function isPaid(): bool
    {
        return $this->payments()->whereIn('status', ['settlement', 'capture'])->exists();
    }

    // Tambahkan relasi yang benar
    public function payments()
    {
        return $this->hasMany(Payments::class, 'rental_id');
    }

    public function latestPayment()
    {
        return $this->hasOne(Payments::class, 'rental_id')->latestOfMany();
    }

    public function getTotalPaidAttribute()
    {
        return $this->payment()->whereIn('status', ['settlement', 'capture'])->sum('amount');
    }
}
