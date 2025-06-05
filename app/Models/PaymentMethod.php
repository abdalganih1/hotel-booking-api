<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // كما هو في مخطط ERD

    protected $fillable = [
        'name',
        'description',
    ];

    // العلاقة
    public function userPaymentMethods()
    {
        return $this->hasMany(UserPaymentMethod::class, 'payment_method_id', 'id');
    }
}