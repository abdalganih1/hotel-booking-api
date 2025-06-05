<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\User;

class HotelSeeder extends Seeder
{
    public function run()
    {
        // إنشاء 5 فنادق عشوائية. كل فندق سيحاول العثور على مسؤول فندق موجود أو قد يُنشئ واحدًا عشوائيًا (ببيانات فريدة)
        Hotel::factory()->count(5)->create();

        // لضمان وجود فندق واحد على الأقل مرتبط بمسؤول فندق معين لغرض الاختبار
        // سنستخدم المسؤول الفندق الثابت الذي أنشأناه في UserSeeder
        $specificHotelAdmin = User::where('username', 'hotel_manager_A')->first();

        if ($specificHotelAdmin) {
            // إذا كان مسؤول الفندق هذا لا يدير فندقًا بعد، قم بإنشاء فندق جديد وربطه به
            if (!$specificHotelAdmin->hotelAdminFor()->exists()) {
                Hotel::factory()->withAdmin($specificHotelAdmin)->create([
                    'name' => 'فندق الأمثلة', // اسم ثابت لهذا الفندق الخاص بالاختبار
                    'location' => 'مكان الاختبار',
                ]);
            }
        } else {
            // في حالة نادرة جداً لم يتم إنشاء مسؤول الفندق في UserSeeder (يجب أن لا تحدث مع firstOrCreate)
            $this->command->warn('Specific hotel admin not found. Creating a new one for test hotel.');
            $newAdmin = User::factory()->hotelAdmin()->create([
                'username' => 'new_hotel_manager_for_test', // تأكد من فرادة هذا الاسم إذا أمكن
                'email' => 'new_hotel_manager_for_test@example.com'
            ]);
            Hotel::factory()->withAdmin($newAdmin)->create([
                'name' => 'فندق اختبار جديد',
                'location' => 'موقع الاختبار الجديد',
            ]);
        }
    }
}