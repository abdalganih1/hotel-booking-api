<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of all bookings.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Booking::with('user', 'room.hotel')->orderBy('booking_date', 'desc');

        // Filtering options
        if ($request->filled('status') && in_array($request->status, ['pending_verification', 'confirmed', 'rejected', 'cancelled'])) {
            $query->where('booking_status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('hotel_id')) {
            $query->whereHas('room.hotel', function ($q) use ($request) {
                $q->where('hotel_id', $request->hotel_id);
            });
        }
        // Add date range filtering if needed

        $bookings = $query->paginate(15);
        $users = \App\Models\User::all(); // For filter dropdown
        $hotels = \App\Models\Hotel::all(); // For filter dropdown

        return view('admin.bookings.index', compact('bookings', 'users', 'hotels'));
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): \Illuminate\View\View
    {
        $booking->load('user', 'room.hotel', 'transactions'); // Load related data
        return view('admin.bookings.show', compact('booking'));
    }

    // No 'create', 'store', 'edit', 'update', 'destroy' for direct booking manipulation by admin here,
    // as bookings are created by users and modified via status changes by hotel admins.
    // Admin typically only views/monitors.
}