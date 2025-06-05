<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']); // حماية المتحكم لدور مدير التطبيق
    }

    /**
     * Display the admin dashboard.
     */
    public function index(): \Illuminate\View\View
    {
        $userCount = User::count();
        $hotelCount = Hotel::count();
        $pendingBookings = Booking::where('booking_status', 'pending_verification')->count();
        $totalRevenue = Transaction::where('reason', 'admin_commission')->sum('amount');

        return view('admin.dashboard', compact('userCount', 'hotelCount', 'pendingBookings', 'totalRevenue'));
    }
}