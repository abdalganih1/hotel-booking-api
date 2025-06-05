<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id'; // كما هو في مخطط ERD

    protected $fillable = [
        'user_id',
        'booking_id',
        'amount',
        'transaction_type',
        'reason',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime', // Converted to datetime for ERD date attribute
        'transaction_type' => 'string',
        'reason' => 'string',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'book_id'); // 'book_id' is PK of bookings
    }
}