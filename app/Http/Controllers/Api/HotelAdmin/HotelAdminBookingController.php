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

class HotelAdminBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:hotel_admin']);
    }

    private function getHotelForAdmin(Request $request)
    {
        return Hotel::where('admin_user_id', $request->user()->user_id)->firstOrFail();
    }

    /**
     * Display a listing of bookings for the admin's hotel. (إدارة حجوزات الفندق)
     */
    public function index(Request $request)
    {
        $hotel = $this->getHotelForAdmin($request);
        $bookings = Booking::where('hotel_id', $hotel->hotel_id)
                            ->with(['user', 'room']) // جلب معلومات المستخدم والغرفة
                            ->latest()
                            ->paginate(15);
        // return new BookingCollection($bookings);
        return response()->json($bookings);
    }

    /**
     * Display the specified booking.
     */
    public function show(Request $request, Booking $booking)
    {
        $hotel = $this->getHotelForAdmin($request);
        if ($booking->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'غير مصرح به لهذا الحجز'], 403);
        }
        // return new BookingResource($booking->load(['user', 'room']));
        return response()->json($booking->load(['user', 'room']));
    }

    /**
     * Update the specified booking's status (approve/reject). (قبول/رفض حجز)
     */
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $hotelAdminUser = $request->user();
        $hotel = $this->getHotelForAdmin($request);

        if ($booking->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'غير مصرح به لهذا الحجز'], 403);
        }

        // TODO: Validation for status
        $request->validate([
            'status' => 'required|in:confirmed,rejected',
        ]);

        // لا يمكن تغيير الحالة إلا إذا كانت 'pending_verification'
        if ($booking->booking_status !== 'pending_verification') {
            return response()->json(['message' => 'لا يمكن تغيير حالة هذا الحجز.'], 400);
        }

        $newStatus = $request->status;
        $booking->booking_status = $newStatus;
        $booking->save();

        // منطق التعامل مع الأرصدة عند تغيير الحالة
        if ($newStatus === 'confirmed') {
            // TODO: إنشاء معاملة لعمولة الفندق (80%)
            Transaction::create([
                'user_id' => $hotelAdminUser->user_id,
                'transaction_type' => 'credit',
                'amount' => $booking->total_price * 0.80,
                'reason' => 'hotel_commission',
                'booking_id' => $booking->booking_id,
                'transaction_date' => now(),
            ]);

            // TODO: إشعار مدير التطبيق لإنشاء معاملة عمولة التطبيق (20%)
            // هذا يمكن أن يتم عبر event & listener أو job
            // $appAdmin = User::where('role', 'app_admin')->first(); // يجب أن يكون هناك مدير واحد
            // if ($appAdmin) {
            //     Transaction::create([
            //         'user_id' => $appAdmin->user_id,
            //         'transaction_type' => 'credit',
            //         'amount' => $booking->total_price * 0.20,
            //         'reason' => 'admin_commission',
            //         'booking_id' => $booking->booking_id,
            //         'transaction_date' => now(),
            //     ]);
            // }

        } elseif ($newStatus === 'rejected') {
            // TODO: إنشاء معاملة لإعادة المبلغ للمستخدم الذي قام بالحجز
            Transaction::create([
                'user_id' => $booking->user_id,
                'transaction_type' => 'credit', // إعادة رصيد
                'amount' => $booking->total_price,
                'reason' => 'booking_refund',
                'booking_id' => $booking->booking_id,
                'transaction_date' => now(),
            ]);
        }
        // return new BookingResource($booking);
        return response()->json($booking);
    }
}