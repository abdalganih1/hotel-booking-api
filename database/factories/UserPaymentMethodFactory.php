<?php

namespace Database\Factories;

use App\Models\UserPaymentMethod;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPaymentMethodFactory extends Factory
{
    protected $model = UserPaymentMethod::class;

    public function definition()
    {
        // تأكد من وجود مستخدمين وطرق دفع
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $paymentMethod = PaymentMethod::inRandomOrder()->first() ?? PaymentMethod::factory()->create(); // قد تحتاج لـ PaymentMethodFactory إذا لم تكن ثابتة

        return [
            'user_id' => $user->user_id,
            'payment_method_id' => $paymentMethod->id,
        ];
    }
}