<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $booking = Booking::inRandomOrder()->first(); // Try to get an existing booking

        $transactionTypes = ['credit', 'debit'];
        $reasons = ['deposit', 'booking_payment', 'booking_refund', 'hotel_commission', 'admin_commission', 'cancellation_fee', 'transfer'];

        $type = $this->faker->randomElement($transactionTypes);
        $reason = $this->faker->randomElement($reasons);
        $amount = $this->faker->randomFloat(2, 10, 1000);

        // Adjust amount/reason based on type for some realism
        if ($type === 'debit' && $reason === 'deposit') {
            $type = 'credit'; // Deposit should be credit
        }
        if ($type === 'credit' && $reason === 'booking_payment') {
            $type = 'debit'; // Payment should be debit
        }

        return [
            'user_id' => $user->user_id,
            'booking_id' => $booking ? $booking->book_id : null, // Use book_id from Booking model
            'amount' => $amount,
            'transaction_type' => $type,
            'reason' => $reason,
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    // Specific states for commissions
    public function hotelCommission(Booking $booking)
    {
        $hotelAdmin = $booking->room->hotel->adminUser; // Get the hotel admin from booking
        if (!$hotelAdmin) { // If no hotel admin assigned to the hotel, create one
            $hotelAdmin = User::factory()->hotelAdmin()->create();
            $booking->room->hotel->update(['admin_user_id' => $hotelAdmin->user_id]);
        }
        return $this->state(fn (array $attributes) => [
            'user_id' => $hotelAdmin->user_id,
            'booking_id' => $booking->book_id,
            'amount' => $booking->total_price * 0.80,
            'transaction_type' => 'credit',
            'reason' => 'hotel_commission',
        ]);
    }

    public function adminCommission(Booking $booking)
    {
        $appAdmin = User::where('role', 'app_admin')->first() ?? User::factory()->appAdmin()->create();
        return $this->state(fn (array $attributes) => [
            'user_id' => $appAdmin->user_id,
            'booking_id' => $booking->book_id,
            'amount' => $booking->total_price * 0.20,
            'transaction_type' => 'credit',
            'reason' => 'admin_commission',
        ]);
    }
}