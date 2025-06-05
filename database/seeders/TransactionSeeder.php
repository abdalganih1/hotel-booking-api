<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Booking;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // إنشاء 50 معاملة عشوائية
        Transaction::factory()->count(50)->create();

        // إنشاء معاملات عمولة للحجوزات المؤكدة (للتأكد من وجودها)
        $confirmedBookings = Booking::where('booking_status', 'confirmed')->get();
        foreach ($confirmedBookings as $booking) {
            // عمولة الفندق
            Transaction::factory()->hotelCommission($booking)->create();
            // عمولة مدير التطبيق
            Transaction::factory()->adminCommission($booking)->create();
        }

        // إيداع مبدئي لبعض المستخدمين (مثلاً 5 مستخدمين)
        User::inRandomOrder()->take(5)->each(function ($user) {
            Transaction::create([
                'user_id' => $user->user_id,
                'amount' => rand(100, 1000),
                'transaction_type' => 'credit',
                'reason' => 'deposit',
                'transaction_date' => now(),
            ]);
        });
    }
}