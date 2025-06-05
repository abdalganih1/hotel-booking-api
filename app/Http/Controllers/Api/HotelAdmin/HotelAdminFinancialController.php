<?php

namespace App\Http\Controllers\Api\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelAdminFinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:hotel_admin']);
    }

    /**
     * Display financial overview for the hotel admin's hotel.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $hotelAdminUser = Auth::user();
        $hotel = $hotelAdminUser->hotelAdminFor()->firstOrFail();

        // Total earnings from commissions for this hotel admin
        $totalEarnings = Transaction::where('user_id', $hotelAdminUser->user_id)
                                    ->where('reason', 'hotel_commission')
                                    ->sum('amount');

        // Recent commission transactions
        $recentTransactions = Transaction::where('user_id', $hotelAdminUser->user_id)
                                        ->where('reason', 'hotel_commission')
                                        ->with('booking.user') // Load related booking and user for context
                                        ->latest('transaction_date')
                                        ->paginate($request->get('limit', 10));

        return response()->json([
            'hotel_name' => $hotel->name,
            'total_earnings' => number_format($totalEarnings, 2),
            'currency' => 'USD',
            'recent_transactions' => $recentTransactions,
            'message' => 'Financial data retrieved successfully.'
        ]);
    }
}