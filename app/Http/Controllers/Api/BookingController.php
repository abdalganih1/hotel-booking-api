<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Transaction; // For financial operations
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // For database transactions

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the user's bookings.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $bookings = Booking::where('user_id', $user->user_id)
                            ->with(['room.hotel', 'user']) // Eager load room and hotel (via room)
                            ->latest()
                            ->paginate($request->get('limit', 10));

        return response()->json($bookings);
    }

    /**
     * Store a newly created booking.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'room_id' => ['required', Rule::exists('rooms', 'room_id')],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'user_notes' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room = Room::findOrFail($request->room_id);

        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $durationNights = $checkOut->diffInDays($checkIn);
        $totalPrice = $durationNights * $room->price_per_night;

        // TODO: Real-world availability check: ensure room is available for selected dates
        // This is complex and might involve checking existing bookings for overlaps.
        // For simplicity, we assume availability for now.

        // Financial Logic: User's balance check and debit
        // This requires the 'transactions' table to accurately represent balance.
        $currentBalance = Transaction::where('user_id', $user->user_id)
                                    ->sum(DB::raw('CASE WHEN transaction_type = "credit" THEN amount ELSE -amount END'));

        if ($currentBalance < $totalPrice) {
            return response()->json(['message' => 'Insufficient balance. Please add funds.'], 400);
        }

        DB::beginTransaction();
        try {
            // Debit user's balance for the booking
            Transaction::create([
                'user_id' => $user->user_id,
                'booking_id' => null, // Will be updated after booking creation
                'amount' => $totalPrice,
                'transaction_type' => 'debit',
                'reason' => 'booking_payment',
                'transaction_date' => now(),
            ]);

            $booking = Booking::create([
                'user_id' => $user->user_id,
                'room_id' => $room->room_id,
                // Denormalized hotel_id as per previous discussions for easier access
                'hotel_id' => $room->hotel->hotel_id,
                'booking_status' => 'pending_verification',
                'booking_date' => Carbon::now(),
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'duration_nights' => $durationNights,
                'total_price' => $totalPrice,
                'user_notes' => $request->user_notes,
            ]);

            // Update the booking_id for the transaction
            Transaction::where('user_id', $user->user_id)
                       ->whereNull('booking_id')
                       ->where('transaction_type', 'debit')
                       ->where('reason', 'booking_payment')
                       ->where('amount', $totalPrice) // To prevent updating wrong transaction
                       ->latest()
                       ->first()
                       ->update(['booking_id' => $booking->book_id]);

            DB::commit();
            return response()->json(['booking' => $booking->load(['room.hotel']), 'message' => 'Booking request submitted successfully. Pending verification.'], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create booking: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified booking for the authenticated user.
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Booking $booking)
    {
        if ($request->user()->user_id !== $booking->user_id) {
            return response()->json(['message' => 'Unauthorized to view this booking.'], 403);
        }
        return response()->json($booking->load(['room.hotel', 'user', 'transactions']));
    }

    /**
     * Request cancellation of a booking by the user.
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestCancellation(Request $request, Booking $booking)
    {
        $user = $request->user();

        if ($user->user_id !== $booking->user_id) {
            return response()->json(['message' => 'Unauthorized to cancel this booking.'], 403);
        }

        // Only allow cancellation if pending or confirmed and check-in date is in future
        if (!in_array($booking->booking_status, ['pending_verification', 'confirmed'])) {
            return response()->json(['message' => 'Booking cannot be cancelled in its current status.'], 400);
        }
        if ($booking->check_in_date->isPast()) {
            return response()->json(['message' => 'Cannot cancel booking after check-in date.'], 400);
        }

        DB::beginTransaction();
        try {
            $booking->booking_status = 'cancelled';
            $booking->save();

            // Refund the user
            Transaction::create([
                'user_id' => $user->user_id,
                'booking_id' => $booking->book_id,
                'amount' => $booking->total_price,
                'transaction_type' => 'credit',
                'reason' => 'booking_refund',
                'transaction_date' => now(),
            ]);

            // TODO: If commissions were already paid to hotel_admin/app_admin for a confirmed booking,
            // you might need to handle clawbacks or record reversal transactions.
            // This can get complex based on your cancellation policy.

            DB::commit();
            return response()->json(['message' => 'Booking cancelled successfully. Amount refunded to your balance.', 'booking' => $booking->load(['room.hotel'])]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to cancel booking: ' . $e->getMessage()], 500);
        }
    }
}