<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function rental()
    {
        return $this->belongsTo(Rentals::class, 'rental_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    protected $casts = [
        'return_date' => 'date',
        'fine_amount' => 'decimal:2'
    ];

    public function calculateFine()
    {
        $rentalEnd = Carbon::parse($this->rental->rental_end);
        $returnDate = Carbon::parse($this->return_date);

        // Hitung selisih hari
        $daysLate = $rentalEnd->diffInDays($returnDate, false);

        // jika return date > rental end
        if ($daysLate > 0) {
            $findPerDay = 1000;
            return $daysLate * $findPerDay;
        }

        return 0;
    }

    public function isLate()
    {
        $rentalEnd = Carbon::parse($this->rental->rental_end);
        $returnDate = Carbon::parse($this->return_date);

        return $returnDate->greaterThan($rentalEnd);
    }

    public function getLetDays()
    {
        $rentalEnd = Carbon::parse($this->rental->rental_end);
        $returnDate = Carbon::parse($this->return_date);

        $daysLate = $rentalEnd->diffInDays($returnDate, false);

        return $daysLate > 0 ? $daysLate : 0;
    }

    public function getLetStatus()
    {
        if($this->isLate()) {
            return 'Telat';
        }

        return 'Tepat Waktu';
    } 
}
