<?php

namespace App\Http\Controllers\Web\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:hotel_admin']);
    }

    /**
     * Display the hotel admin dashboard.
     */
    public function index(): \Illuminate\View\View
    {
        $hotelAdminUser = Auth::user();
        $hotel = $hotelAdminUser->hotelAdminFor; // العلاقة hotelAdminFor في User Model

        if (!$hotel) {
            // إذا لم يكن مسؤول الفندق مرتبطًا بأي فندق، يمكن إعادة توجيهه
            return view('hotel_admin.no_hotel_assigned');
        }

        $totalRooms = $hotel->rooms()->count();
        $totalBookings = Booking::where('hotel_id', $hotel->hotel_id)->count(); // Denormalized hotel_id
        $pendingBookings = Booking::where('hotel_id', $hotel->hotel_id)
                                  ->where('booking_status', 'pending_verification')
                                  ->count();
        $totalEarnings = Transaction::where('user_id', $hotelAdminUser->user_id)
                                    ->where('reason', 'hotel_commission')
                                    ->sum('amount');

        return view('hotel_admin.dashboard', compact(
            'hotel',
            'totalRooms',
            'totalBookings',
            'pendingBookings',
            'totalEarnings'
        ));
    }
}