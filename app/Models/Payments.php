<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Payments extends Model
{
    use LogsActivity, HasFactory;

    protected $fillable = [
        'rental_id',
        'order_id',
        'payment_type',
        'status',
        'amount',
        'va_number',
        'bank',
        'midtrans_response',
        'paid_at',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'paid_at' => 'datetime',
        'amount' => 'integer'
    ];

    public function rental() 
    {
        return $this->belongsTo(Rentals::class, 'rental_id');
    }

    public function scopePeding($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSucces($query)
    {
        return $query->whereIn('status', ['settlement', 'capture']);
    }

    public function isSucces(): bool
    {
        return in_array($this->status, ['settlement', 'capture']);
    }

    public static function generateOrderId($rentalId): string
    {
        // Format: INV-{rental_id}-{timestamp}
        return 'INV-' . $rentalId . '-' . time();
        
        // Atau jika ingin lebih random:
        // return 'INV-' . $rentalId . '-' . uniqid() . '-' . time();
    }

}
