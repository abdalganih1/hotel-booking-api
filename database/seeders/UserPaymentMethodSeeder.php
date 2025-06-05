<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserPaymentMethod;
use App\Models\User;
use App\Models\PaymentMethod;

class UserPaymentMethodSeeder extends Seeder
{
    public function run()
    {
        // تأكد من وجود طرق دفع ومستخدمين
        if (PaymentMethod::count() === 0) {
            $this->call(PaymentMethodSeeder::class);
        }
        if (User::count() === 0) {
            $this->call(UserSeeder::class);
        }

        // ربط 10-20 مستخدمًا بطرق دفع عشوائية
        User::inRandomOrder()->take(rand(10, 20))->each(function ($user) {
            $paymentMethods = PaymentMethod::inRandomOrder()->take(rand(1, 3))->get(); // كل مستخدم لديه 1-3 طرق دفع
            foreach ($paymentMethods as $pm) {
                // تجنب التكرار إذا كان هناك قيد فريد
                if (!UserPaymentMethod::where('user_id', $user->user_id)->where('payment_method_id', $pm->id)->exists()) {
                    UserPaymentMethod::create([
                        'user_id' => $user->user_id,
                        'payment_method_id' => $pm->id,
                    ]);
                }
            }
        });
    }
}