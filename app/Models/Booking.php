<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'book_id';

    protected $fillable = [
        'user_id',
        'room_id',
        'hotel_id', // أضف هذا
        'booking_status',
        'booking_date',
        'check_in_date',
        'check_out_date',
        'duration_nights',
        'total_price',
        'user_notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'booking_status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // ********* إضافة العلاقة المباشرة للفندق *********
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }
    // ************************************************

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'booking_id', 'book_id');
    }
}