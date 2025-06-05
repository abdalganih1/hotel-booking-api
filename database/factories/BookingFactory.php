<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        $user = User::where('role', 'user')->inRandomOrder()->first() ?? User::factory()->user()->create();
        $room = Room::inRandomOrder()->first() ?? Room::factory()->create();

        $checkInDate = $this->faker->dateTimeBetween('-3 months', '+3 months');
        $durationNights = $this->faker->numberBetween(1, 10);
        $checkOutDate = (clone $checkInDate)->modify('+' . $durationNights . ' days');
        $totalPrice = $durationNights * $room->price_per_night;

        $status = $this->faker->randomElement(['pending_verification', 'confirmed', 'rejected', 'cancelled']);

        return [
            'user_id' => $user->user_id,
            'room_id' => $room->room_id,
            'booking_status' => $status,
            'booking_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'duration_nights' => $durationNights,
            'total_price' => $totalPrice,
            'user_notes' => $this->faker->boolean(30) ? $this->faker->sentence() : null,
        ];
    }
}