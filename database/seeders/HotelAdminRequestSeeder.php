<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelAdminRequest;

class HotelAdminRequestSeeder extends Seeder
{
    public function run()
    {
        // إنشاء 10 طلبات مسؤول فندق (حالات مختلفة: معلقة، مقبولة، مرفوضة)
        HotelAdminRequest::factory()->count(10)->create();
    }
}