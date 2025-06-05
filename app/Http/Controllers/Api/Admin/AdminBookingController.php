<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']); // Protect all methods
    }

    /**
     * Display a listing of all bookings (global view for App Admin).
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = Booking::with('user', 'room.hotel')->latest(); // Eager load relations

        // Filtering options
        if ($request->filled('status') && in_array($request->status, ['pending_verification', 'confirmed', 'rejected', 'cancelled'])) {
            $query->where('booking_status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('hotel_id')) {
            $query->whereHas('room.hotel', function ($q) use ($request) {
                $q->where('hotel_id', $request->input('hotel_id'));
            });
        }
        // Add date range filtering if needed

        $bookings = $query->paginate($request->input('per_page', 15));
        // You might use a BookingCollection Resource here
        return response()->json($bookings);
    }

    /**
     * Display the specified booking details (global view for App Admin).
     */
    public function show(Booking $booking): \Illuminate\Http\JsonResponse
    {
        $booking->load('user', 'room.hotel', 'transactions'); // Eager load relations
        // You might use a BookingResource here
        return response()->json($booking);
    }

    // update, store, destroy methods are not implemented here as app admin typically views/monitors
    // or status changes are handled by hotel admin, or by a specific endpoint for processing commissions.
}