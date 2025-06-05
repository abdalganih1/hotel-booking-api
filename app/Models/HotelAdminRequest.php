<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelAdminRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'request_id'; // كما هو في مخطط ERD

    protected $fillable = [
        'user_id',
        'requested_hotel_name',
        'requested_hotel_location',
        'requested_contact_phone',
        'requested_photos_json',
        'requested_videos_json', // تم إضافة هذا الحقل
        'request_notes',
        'request_status',
        'reviewed_by_user_id',
        'review_timestamp',
    ];

    protected $casts = [
        'requested_photos_json' => 'array',
        'requested_videos_json' => 'array',
        'review_timestamp' => 'datetime',
        'request_status' => 'string', // ENUMs are cast to string for type safety
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id', 'user_id');
    }
}