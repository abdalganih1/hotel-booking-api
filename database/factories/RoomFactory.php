<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition()
    {
        return [
            'hotel_id' => Hotel::inRandomOrder()->first()->hotel_id ?? Hotel::factory()->create()->hotel_id,
            'max_occupancy' => $this->faker->numberBetween(1, 5),
            'price_per_night' => $this->faker->randomFloat(2, 50, 500),
            'services' => $this->faker->randomElement([
                'WiFi, Breakfast',
                'AC, TV',
                'Pool Access, Gym',
                'Mini Bar, Room Service',
            ]),
            'photos_json' => json_encode([
                $this->faker->imageUrl(640, 480, 'rooms', true),
                $this->faker->imageUrl(640, 480, 'rooms', true)
            ]),
            'videos_json' => json_encode([]),
            'payment_link' => $this->faker->boolean(20) ? $this->faker->url() : null, // 20% chance to have a payment link
            'notes' => $this->faker->sentence(),
        ];
    }
}