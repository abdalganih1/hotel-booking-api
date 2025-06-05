<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
// use App\Http\Resources\BookingResource;
// use App\Http\Resources\BookingCollection;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // حماية جميع الدوال
    }

    /**
     * Display a listing of the user's bookings. (عرض سجل الحجوزات الشخصية)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $bookings = Booking::where('user_id', $user->user_id)
                            ->with(['room.hotel']) // جلب معلومات الغرفة والفندق
                            ->latest()
                            ->paginate(10);
        // return new BookingCollection($bookings);
        return response()->json($bookings);
    }

    /**
     * Store a newly created booking in storage. (حجز غرفة)
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,room_id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'user_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room = Room::findOrFail($request->room_id);

        // TODO: Check room availability for the selected dates
        // TODO: Check user balance (هذه عملية معقدة قد تتضمن transactions)

        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $durationNights = $checkOut->diffInDays($checkIn);
        $totalPrice = $durationNights * $room->price_per_night;

        // خصم الرصيد (مثال مبسط، يجب أن يكون transaction آمن)
        // if ($user->balance < $totalPrice) {
        //     return response()->json(['message' => 'رصيد غير كافٍ'], 400);
        // }
        // $user->decrement('balance', $totalPrice); // مثال

        $booking = Booking::create([
            'user_id' => $user->user_id,
            'room_id' => $room->room_id,
            'hotel_id' => $room->hotel_id, // denormalized
            'booking_status' => 'pending_verification', // الحالة الأولية
            'booking_date' => Carbon::now(),
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'duration_nights' => $durationNights,
            'total_price' => $totalPrice,
            'user_notes' => $request->user_notes,
        ]);

        // TODO: إنشاء معاملة مالية (transaction) لخصم المبلغ
        // Transaction::create([...]);

        // return new BookingResource($booking->load(['room.hotel']));
        return response()->json($booking->load(['room.hotel']), 201);
    }

    /**
     * Display the specified booking.
     */
    public function show(Request $request, Booking $booking)
    {
        // TODO: Authorization - Ensure the user owns this booking
        if ($request->user()->user_id !== $booking->user_id) {
            return response()->json(['message' => 'غير مصرح به'], 403);
        }
        // return new BookingResource($booking->load(['room.hotel']));
        return response()->json($booking->load(['room.hotel']));
    }


    /**
     * Request cancellation of a booking. (طلب إلغاء حجز)
     */
    public function requestCancellation(Request $request, Booking $booking)
    {
        // TODO: Authorization - Ensure the user owns this booking
        if ($request->user()->user_id !== $booking->user_id) {
            return response()->json(['message' => 'غير مصرح به'], 403);
        }

        // TODO: Logic for cancellation (e.g., check if cancellable, apply fees)
        // لا يمكن الإلغاء إلا إذا كان الحجز قيد التحقق أو مؤكد (ولم يمضِ وقت الإقامة)
        if (!in_array($booking->booking_status, ['pending_verification', 'confirmed'])) {
             return response()->json(['message' => 'لا يمكن إلغاء هذا الحجز في حالته الحالية'], 400);
        }

        $booking->booking_status = 'cancelled'; // أو 'cancellation_requested'
        $booking->save();

        // TODO: إنشاء معاملة مالية (transaction) لاسترجاع المبلغ (إذا كان ذلك ممكنًا)
        // Transaction::create([...]);

        // return new BookingResource($booking);
        return response()->json(['message' => 'تم طلب إلغاء الحجز بنجاح', 'booking' => $booking]);
    }
}