<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // مدير التطبيق الرئيسي - استخدم firstOrCreate لتجنب التكرار
        User::firstOrCreate(
            ['username' => 'app_admin'], // شروط البحث
            [
                'password' => Hash::make('password'),
                'role' => 'app_admin',
                'first_name' => 'مدير',
                'last_name' => 'التطبيق',
                'phone_number' => '0900000001',
                'email' => 'admin@example.com',
                'email_verified_at' => now(), // مهم لـ Breeze
            ]
        );

        // مسؤول فندق - استخدم firstOrCreate لتجنب التكرار
        User::firstOrCreate(
            ['username' => 'hotel_manager_A'], // شروط البحث
            [
                'password' => Hash::make('password'),
                'role' => 'hotel_admin',
                'first_name' => 'مسؤول فندق',
                'last_name' => 'أ',
                'phone_number' => '0900000002',
                'email' => 'hoteladmin@example.com',
                'email_verified_at' => now(), // مهم لـ Breeze
            ]
        );

        // مستخدم عادي 1 - يمكن أيضاً استخدام firstOrCreate إذا أردت التأكد من فرادته
        User::firstOrCreate(
            ['username' => 'regular_user1'],
            [
                'password' => Hash::make('password'),
                'role' => 'user',
                'first_name' => 'مستخدم',
                'last_name' => 'عادي1',
                'phone_number' => '0900000003',
                'address' => 'العنوان الأول، المدينة',
                'gender' => 'male',
                'age' => 30,
                'email' => 'user1@example.com',
                'email_verified_at' => now(),
            ]
        );

        // مستخدم عادي 2
        User::firstOrCreate(
            ['username' => 'regular_user2'],
            [
                'password' => Hash::make('password'),
                'role' => 'user',
                'first_name' => 'مستخدمة',
                'last_name' => 'عادية2',
                'phone_number' => '0900000004',
                'address' => 'العنوان الثاني، المدينة',
                'gender' => 'female',
                'age' => 25,
                'email' => 'user2@example.com',
                'email_verified_at' => now(),
            ]
        );

        // إنشاء عدد إضافي من المستخدمين العشوائيين (إذا لم يتم إنشاءهم مسبقاً)
        User::factory()->count(15)->create(); // سيستخدم definition() العادية التي تولد بيانات فريدة
    }
}