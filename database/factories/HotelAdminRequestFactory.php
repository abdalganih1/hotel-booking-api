<?php

namespace Database\Factories;

use App\Models\HotelAdminRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelAdminRequestFactory extends Factory
{
    protected $model = HotelAdminRequest::class;

    public function definition()
    {
        // تأكد من وجود مستخدمين
        $user = User::where('role', 'user')->inRandomOrder()->first() ?? User::factory()->user()->create();
        $appAdmin = User::where('role', 'app_admin')->inRandomOrder()->first() ?? User::factory()->appAdmin()->create();

        $status = $this->faker->randomElement(['pending', 'approved', 'rejected']);

        return [
            'user_id' => $user->user_id,
            'requested_hotel_name' => $this->faker->company() . ' Plaza',
            'requested_hotel_location' => $this->faker->address(),
            'requested_contact_phone' => $this->faker->phoneNumber(),
            'requested_photos_json' => json_encode([$this->faker->imageUrl()]),
            'requested_videos_json' => json_encode([]),
            'request_notes' => $this->faker->paragraph(1),
            'request_status' => $status,
            'reviewed_by_user_id' => ($status != 'pending') ? $appAdmin->user_id : null,
            'review_timestamp' => ($status != 'pending') ? $this->faker->dateTimeBetween('-1 year', 'now') : null,
        ];
    }
}