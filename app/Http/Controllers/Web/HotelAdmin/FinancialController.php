<?php

namespace App\Http\Controllers\Web\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:hotel_admin']);
    }

    /**
     * Display financial overview for the hotel admin's hotel.
     */
    public function index(): \Illuminate\View\View
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
                                        ->with('booking.user') // Load related booking and user
                                        ->latest()
                                        ->take(10)
                                        ->get();

        return view('hotel_admin.financials.index', compact('hotel', 'totalEarnings', 'recentTransactions'));
    }
}
