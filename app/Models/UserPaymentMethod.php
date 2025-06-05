<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPaymentMethod extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // كما هو في مخطط ERD

    protected $fillable = [
        'user_id',
        'payment_method_id',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
}