<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class, // يجب أن يأتي أولاً لإنشاء المستخدمين
            PaymentMethodSeeder::class, // طرق الدفع
            FaqSeeder::class, // يعتمد على المستخدمين
            HotelSeeder::class, // يعتمد على المستخدمين (مسؤولي الفنادق)
            RoomSeeder::class, // يعتمد على الفنادق
            HotelAdminRequestSeeder::class, // يعتمد على المستخدمين
            BookingSeeder::class, // يعتمد على المستخدمين والغرف (والفنادق ضمنيًا)
            TransactionSeeder::class, // يعتمد على المستخدمين والحجوزات
            UserPaymentMethodSeeder::class, // يعتمد على المستخدمين وطرق الدفع
        ]);
    }
}