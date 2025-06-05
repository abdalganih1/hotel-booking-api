<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail // Keep MustVerifyEmail for Breeze compatibility
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id'; // كما هو في مخطط ERD

    protected $fillable = [
        'username',
        'password', // كما هو مطلوب في السؤال
        'role',
        'first_name',
        'last_name',
        'phone_number',
        'address',
        'gender',
        'age',
        'email', // للحفاظ على توافقية Breeze
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel يقوم بتجزئة (hashing) كلمة المرور تلقائياً
        'role' => 'string',
        'gender' => 'string',
        'phone_number' => 'string', // للتأكد من التعامل معه كنص
    ];

    // العلاقات
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'user_id', 'user_id');
    }

    public function hotelAdminFor()
    {
        return $this->hasOne(Hotel::class, 'admin_user_id', 'user_id');
    }

    public function hotelAdminRequests()
    {
        return $this->hasMany(HotelAdminRequest::class, 'user_id', 'user_id');
    }

    public function reviewedHotelAdminRequests()
    {
        return $this->hasMany(HotelAdminRequest::class, 'reviewed_by_user_id', 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'user_id');
    }

    public function userPaymentMethods()
    {
        return $this->hasMany(UserPaymentMethod::class, 'user_id', 'user_id');
    }

    // دالة مساعدة لتحديد الدور (تستخدم في RoleMiddleware)
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }
}