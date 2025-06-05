<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'book_id'; // كما هو في مخطط ERD

    protected $fillable = [
        'user_id',
        'room_id',
        'booking_status',
        'booking_date',
        'check_in_date',
        'check_out_date',
        'duration_nights',
        'total_price',
        'user_notes',
    ];

    protected $casts = [
        'booking_date' => 'date', // Converted to date for ERD date attribute
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'booking_status' => 'string',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // ******** إضافة العلاقة الجديدة هنا *********
    public function hotel()
    {
        // علاقة HasOneThrough: حجز -> غرفة -> فندق
        // يربط نموذج Booking بالفندق عبر نموذج Room
        // الوسيط الأول: Room::class
        // المفتاح الأجنبي في Rooms الذي يربطها بالـ Bookings (ليس موجوداً بشكل مباشر، لكن Room لديه booking_id عبر علاقة hasMany في Room Model)
        // المفتاح المحلي في Rooms الذي يربطها بالـ Hotels (hotel_id)
        // المفتاح الأجنبي في Hotels (hotel_id)
        // المفتاح المحلي في Bookings الذي يربطها بالـ Rooms (room_id)

        // الطريقة الأبسط والأكثر شيوعاً هي تعريفها كـ accessor إذا كان الوصول عبر room
        // أو استخدام hasOneThrough إذا كانت العلاقة معقدة أكثر
        // بما أنك تستخدم `room.hotel` في `with()`, فهذا يعني أنك تتوقع علاقة مباشرة أو أن `room` نفسها لديها `hotel` معرفة.
        // الأسهل هو الوصول إليها هكذا: `$booking->room->hotel`
        // إذا كنت تريد علاقة مباشرة باسم `hotel` في Booking Model، يمكنك تعريفها كالتالي:
        return $this->hasOneThrough(
            Hotel::class, // النموذج النهائي الذي نريد الوصول إليه (الفندق)
            Room::class,  // النموذج الوسيط (الغرفة)
            'room_id',    // المفتاح الأجنبي على جدول الغرف الذي يربطها بالـ Bookings (ليس Room ID، بل المفتاح في Room model) -> هذا الخاطئ.

            // الطريقة الصحيحة لـ hasOneThrough إذا كان الـ booking_id في الغرفة:
            // room_id في جدول Bookings هو مفتاح يربط إلى Room
            // hotel_id في جدول Rooms هو مفتاح يربط إلى Hotel

            'hotel_id',   // المفتاح الأجنبي على جدول الغرف الذي يربطها بالفندق (hotel_id في Rooms)
            'room_id',    // المفتاح المحلي على جدول الحجوزات الذي يربطها بالغرفة (room_id في Bookings)
            'hotel_id'    // المفتاح المحلي على جدول الغرف الذي يربطها بنفسها (Primary Key of Rooms, which is room_id)
        );
        // قد يكون تعريف HasOneThrough هذا معقداً قليلاً، تأكد من فهمه جيداً
        // الأسهل للتصحيح هو `$query->with('room.hotel')` في المتحكم إذا كنت تريد eager load.

        // إذا كنت تريد فقط الوصول السهل:
        // return $this->room->hotel; // هذا سيكون accessor وليس علاقة eloquent
    }
    // **********************************************

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'booking_id', 'book_id'); // 'book_id' is PK of bookings
    }
}