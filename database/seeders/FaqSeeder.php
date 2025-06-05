<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;
use App\Models\User;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        Faq::create([
            'user_id' => $user->user_id,
            'question' => 'كيف يمكنني حجز غرفة؟',
            'answer' => 'يمكنك حجز غرفة عن طريق تصفح الفنادق المتاحة واختيار الغرفة المناسبة ثم اتباع خطوات الحجز.'
        ]);
        Faq::create([
            'user_id' => $user->user_id,
            'question' => 'هل يمكنني إلغاء الحجز؟',
            'answer' => 'نعم، يمكنك طلب إلغاء الحجز وفقًا لسياسة الإلغاء الخاصة بالفندق. قد يتم تطبيق رسوم.'
        ]);
        Faq::create([
            'user_id' => $user->user_id,
            'question' => 'كيف أضيف رصيد إلى حسابي؟',
            'answer' => 'يمكنك إضافة رصيد من خلال صفحة إدارة الرصيد واختيار وسيلة الدفع المناسبة.'
        ]);
        Faq::create([
            'user_id' => $user->user_id,
            'question' => 'ما هي أنواع الغرف المتاحة؟',
            'answer' => 'تختلف أنواع الغرف حسب الفندق، ولكنها تشمل عادةً الغرف المفردة والمزدوجة والأجنحة العائلية.'
        ]);
    }
}