<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition()
    {
        // محاولة إيجاد admin_user_id لديه دور 'hotel_admin' وليس لديه فندق بالفعل
        $adminUser = User::where('role', 'hotel_admin')
                         ->whereDoesntHave('hotelAdminFor')
                         ->inRandomOrder()
                         ->first();

        return [
            'name' => $this->faker->company() . ' Hotel',
            'location' => $this->faker->city() . ', ' . $this->faker->address(),
            'rating' => $this->faker->randomFloat(1, 3.0, 5.0),
            'notes' => $this->faker->paragraph(2),
            'contact_person_phone' => $this->faker->phoneNumber(),
            'admin_user_id' => $adminUser ? $adminUser->user_id : null, // يمكن أن يكون null إذا لم يكن هناك مسؤول متاح
            'photos_json' => json_encode([
                // $this->faker->imageUrl(640, 480, 'hotels', true),
                // $this->faker->imageUrl(640, 480, 'hotels', true)
            ]),
            'videos_json' => json_encode([]), // يمكن إضافة URLs وهمية إذا لزم الأمر
        ];
    }

    public function withAdmin(User $admin)
    {
        return $this->state(fn (array $attributes) => [
            'admin_user_id' => $admin->user_id,
        ]);
    }
}