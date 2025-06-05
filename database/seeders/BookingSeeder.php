<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    public function run()
    {
        // إنشاء 30 حجزًا
        Booking::factory()->count(30)->create();
    }
}