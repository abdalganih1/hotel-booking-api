<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Hotel;

class RoomSeeder extends Seeder
{
    public function run()
    {
        // لكل فندق، قم بإنشاء 3-5 غرف
        Hotel::all()->each(function ($hotel) {
            Room::factory()->count(rand(3, 5))->create(['hotel_id' => $hotel->hotel_id]);
        });
    }
}