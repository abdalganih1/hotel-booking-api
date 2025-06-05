<?php

namespace App\Http\Controllers\Api\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Transaction; // For commissions
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Http\Resources\BookingResource;
// use App\Http\Resources\BookingCollection;
use App\Models\User; // Needed for app admin commission
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class HotelAdminBookingController extends Controller
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
        return Auth::user()->hotelAdminFor()->firstOrFail();
    }

    /**
     * Display a listing of bookings for the authenticated admin's hotel.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $hotel = $this->getHotelForAdmin();
        $query = Booking::where('hotel_id', $hotel->hotel_id)
                        ->with(['user', 'room.hotel']); // Eager load user and room with hotel

        // Filtering by status
        if ($request->filled('status')) {
            $query->where('booking_status', $request->status);
        }

        $bookings = $query->latest('booking_date')->paginate($request->get('limit', 15));
        return response()->json($bookings);
    }

    /**
     * Display the specified booking if it belongs to the authenticated admin's hotel.
     * @param Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Booking $booking)
    {
        $hotel = $this->getHotelForAdmin();
        if ($booking->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'Unauthorized to view this booking.'], 403);
        }
        $booking->load(['user', 'room.hotel', 'transactions']);
        return response()->json($booking);
    }

    /**
     * Update the status of the specified booking (confirm/reject) by the hotel admin.
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $hotelAdminUser = Auth::user();
        $hotel = $this->getHotelForAdmin();

        if ($booking->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'Unauthorized to update this booking.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', Rule::in(['confirmed', 'rejected'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($booking->booking_status !== 'pending_verification') {
            return response()->json(['message' => 'Cannot change status for a non-pending booking.'], 400);
        }

        $newStatus = $request->status;
        $booking->booking_status = $newStatus;

        DB::beginTransaction();
        try {
            $booking->save(); // Save status change first

            if ($newStatus === 'confirmed') {
                // Ensure commissions are processed only once
                $commissionExists = Transaction::where('booking_id', $booking->book_id)
                                                ->whereIn('reason', ['hotel_commission', 'admin_commission'])
                                                ->exists();
                if (!$commissionExists) {
                    // Process Hotel Commission (80%)
                    Transaction::create([
                        'user_id' => $hotelAdminUser->user_id,
                        'booking_id' => $booking->book_id,
                        'amount' => $booking->total_price * 0.80,
                        'transaction_type' => 'credit',
                        'reason' => 'hotel_commission',
                        'transaction_date' => now(),
                    ]);

                    // Process App Admin Commission (20%)
                    $appAdminUser = User::where('role', 'app_admin')->first();
                    if ($appAdminUser) {
                        Transaction::create([
                            'user_id' => $appAdminUser->user_id,
                            'booking_id' => $booking->book_id,
                            'amount' => $booking->total_price * 0.20,
                            'transaction_type' => 'credit',
                            'reason' => 'admin_commission',
                            'transaction_date' => now(),
                        ]);
                    }
                } else {
                    // Commissions already exist, proceed but notify (or return error if strict)
                    // For now, we allow status update but warn if commissions exist
                }
            } elseif ($newStatus === 'rejected') {
                // Refund user for booking payment
                Transaction::create([
                    'user_id' => $booking->user->user_id,
                    'booking_id' => $booking->book_id,
                    'amount' => $booking->total_price,
                    'transaction_type' => 'credit', // Refund is credit to user
                    'reason' => 'booking_refund',
                    'transaction_date' => now(),
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Booking status updated to ' . $newStatus . '.', 'booking' => $booking->load(['room.hotel'])]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update booking status: ' . $e->getMessage()], 500);
        }
    }
}