<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $primaryKey = 'hotel_id';

    protected $fillable = [
        'name',
        'location',
        'rating',
        'notes',
        'contact_person_phone',
        'admin_user_id',
        'photos_json',
        'videos_json',
    ];

    protected $casts = [
        'rating' => 'float',
        // أزل photos_json و videos_json من هنا، لأننا سنقوم بالـ casting يدوياً في accessors
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

        // --- التعديل هنا: استخدام stripslashes قبل json_decode ---
        $cleanValue = stripslashes($value); // إزالة أي علامات هروب زائدة
        $decoded = json_decode($cleanValue, true);
        // --- نهاية التعديل ---

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // فلترة أي قيم فارغة أو null من المصفوفة الناتجة
            return array_filter($decoded);
        }

        // إذا فشل الفك أو لم يكن JSON صالحًا، أعد مصفوفة فارغة
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
        // تأكد أن القيمة دائماً مصفوفة قبل التشفير
        if (!is_array($value)) {
            $value = [];
        }
        $this->attributes['photos_json'] = json_encode(array_filter($value)); // شفر كـ JSON
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

        // --- التعديل هنا: استخدام stripslashes قبل json_decode ---
        $cleanValue = stripslashes($value);
        $decoded = json_decode($cleanValue, true);
        // --- نهاية التعديل ---

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
    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id', 'user_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id', 'hotel_id');
    }
}