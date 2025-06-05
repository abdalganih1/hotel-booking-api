<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        // هذه البيانات ثابتة ولا تحتاج لـ Factory
        PaymentMethod::create(['name' => 'بطاقة ائتمان', 'description' => 'الدفع باستخدام بطاقة الائتمان']);
        PaymentMethod::create(['name' => 'باي بال', 'description' => 'الدفع الآمن عبر حساب باي بال']);
        PaymentMethod::create(['name' => 'تحويل بنكي', 'description' => 'الدفع عبر تحويل مباشر للحساب البنكي']);
        PaymentMethod::create(['name' => 'الدفع عند الوصول', 'description' => 'الدفع النقدي أو بالبطاقة عند الوصول للفندق']);
    }
}