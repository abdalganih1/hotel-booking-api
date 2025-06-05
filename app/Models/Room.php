<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'room_id'; // كما هو في مخطط ERD

    protected $fillable = [
        'hotel_id',
        'max_occupancy',
        'price_per_night',
        'services', // تم تصحيحها من services_offered
        'photos_json',
        'videos_json',
        'payment_link',
        'notes',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        // أزل photos_json و videos_json من هنا، لأننا سنقوم بالـ casting يدوياً في accessors/mutators
    ];

    /**
     * Get the photos_json attribute as an array.
     *
     * @param  string|null  $value
     * @return array
     */
    public function getPhotosJsonAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }
        $cleanValue = stripslashes($value);
        $decoded = json_decode($cleanValue, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_filter($decoded);
        }
        return [];
    }

    /**
     * Set the photos_json attribute (mutator to store as JSON string).
     *
     * @param  array  $value
     * @return void
     */
    public function setPhotosJsonAttribute($value)
    {
        if (!is_array($value)) {
            $value = [];
        }
        $this->attributes['photos_json'] = json_encode(array_filter($value));
    }


    /**
     * Get the videos_json attribute as an array.
     *
     * @param  string|null  $value
     * @return array
     */
    public function getVideosJsonAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }
        $cleanValue = stripslashes($value);
        $decoded = json_decode($cleanValue, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_filter($decoded);
        }
        return [];
    }

    /**
     * Set the videos_json attribute (mutator to store as JSON string).
     *
     * @param  array  $value
     * @return void
     */
    public function setVideosJsonAttribute($value)
    {
        if (!is_array($value)) {
            $value = [];
        }
        $this->attributes['videos_json'] = json_encode(array_filter($value));
    }


    // العلاقات
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id', 'room_id');
    }
}