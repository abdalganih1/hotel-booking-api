<?php

namespace App\Http\Controllers\Api\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
class HotelAdminHotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:hotel_admin']);
    }

    /**
     * Get the hotel managed by the authenticated hotel admin.
     */
    private function getHotelForAdmin(): Hotel
    {
        // This should always succeed because of the middleware chain and seeding
        return Auth::user()->hotelAdminFor()->firstOrFail();
    }

    /**
     * Display details of the hotel managed by the authenticated admin.
     * @return \Illuminate\Http\JsonResponse
     */
    public function showHotelDetails()
    {
        $hotel = $this->getHotelForAdmin();
        $hotel->load('rooms'); // Load rooms for hotel details
        return response()->json($hotel);
    }

    /**
     * Update details of the hotel managed by the authenticated admin.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateHotelDetails(Request $request)
    {
        $hotel = $this->getHotelForAdmin();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('hotels', 'name')->ignore($hotel->hotel_id, 'hotel_id')],
            'location' => ['nullable', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'notes' => ['nullable', 'string'],
            'contact_person_phone' => ['nullable', 'string', 'max:20'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'url', 'max:2048'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['nullable', 'url', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $hotelData = $validator->validated();
        $hotelData['photos_json'] = $hotelData['photos'] ?? [];
        $hotelData['videos_json'] = $hotelData['videos'] ?? [];
        unset($hotelData['photos'], $hotelData['videos']);

        $hotel->update($hotelData);

        return response()->json(['hotel' => $hotel, 'message' => 'Hotel information updated successfully.']);
    }

    /**
     * Display financial overview for the hotel admin's hotel.
     * @return \Illuminate\Http\JsonResponse
     */
    public function showHotelBalance()
    {
        $hotelAdminUser = Auth::user();
        // Total earnings from commissions for this hotel admin
        $totalEarnings = Transaction::where('user_id', $hotelAdminUser->user_id)
                                    ->where('reason', 'hotel_commission')
                                    ->sum('amount');

        // You might want to include recent transactions as well
        $recentTransactions = Transaction::where('user_id', $hotelAdminUser->user_id)
                                        ->where('reason', 'hotel_commission')
                                        ->with('booking.user')
                                        ->latest()
                                        ->take(10)
                                        ->get();

        return response()->json([
            'total_earnings' => number_format($totalEarnings, 2),
            'currency' => 'USD',
            'recent_transactions' => $recentTransactions,
            'message' => 'Financial data retrieved successfully.'
        ]);
    }
}