<?php

namespace App\Http\Controllers\Web\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:hotel_admin']);
    }

    /**
     * Get the hotel managed by the authenticated hotel admin.
     */
    private function getHotelForAdmin(): Hotel
    {
        return Auth::user()->hotelAdminFor()->firstOrFail();
    }

    /**
     * Display a listing of bookings for the admin's hotel.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $hotel = $this->getHotelForAdmin();
        $query = Booking::where('hotel_id', $hotel->hotel_id) // Use denormalized hotel_id
                        ->with(['user', 'room'])
                        ->orderBy('booking_date', 'desc');

        // Filtering by status
        if ($request->filled('status') && in_array($request->status, ['pending_verification', 'confirmed', 'rejected', 'cancelled'])) {
            $query->where('booking_status', $request->status);
        }

        $bookings = $query->paginate(15);
        return view('hotel_admin.bookings.index', compact('hotel', 'bookings'));
    }

    /**
     * Display the specified booking for the admin's hotel.
     */
    public function show(Booking $booking): \Illuminate\View\View
    {
        $hotel = $this->getHotelForAdmin();
        if ($booking->hotel_id !== $hotel->hotel_id) { // Denormalized hotel_id check
            abort(403, 'Unauthorized action.');
        }
        $booking->load(['user', 'room', 'transactions']);
        return view('hotel_admin.bookings.show', compact('booking'));
    }

    /**
     * Update the status of the specified booking (confirm/reject).
     */
    public function updateStatus(Request $request, Booking $booking): \Illuminate\Http\RedirectResponse
    {
        $hotelAdminUser = Auth::user();
        $hotel = $this->getHotelForAdmin();

        if ($booking->hotel_id !== $hotel->hotel_id) { // Denormalized hotel_id check
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => ['required', Rule::in(['confirmed', 'rejected'])],
        ]);

        if ($booking->booking_status !== 'pending_verification') {
            return redirect()->route('hotel_admin.panel.bookings.show', $booking->book_id)
                             ->with('error', __('Cannot change status for a non-pending booking.'));
        }

        $newStatus = $request->status;
        $booking->booking_status = $newStatus;

        DB::beginTransaction();
        try {
            if ($newStatus === 'confirmed') {
                // Check if commissions already processed
                $commissionAlreadyProcessed = Transaction::where('booking_id', $booking->book_id)
                                                        ->where('reason', 'hotel_commission')
                                                        ->exists();
                if (!$commissionAlreadyProcessed) {
                    Transaction::create([
                        'user_id' => $hotelAdminUser->user_id,
                        'booking_id' => $booking->book_id,
                        'amount' => $booking->total_price * 0.80,
                        'transaction_type' => 'credit',
                        'reason' => 'hotel_commission',
                        'transaction_date' => now(),
                    ]);

                    // Trigger/log for app admin commission (20%)
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
                     return redirect()->route('hotel_admin.panel.bookings.show', $booking->book_id)
                                     ->with('error', __('Commissions already processed for this booking.'));
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
            $booking->save(); // Save status after transaction
            DB::commit();
            return redirect()->route('hotel_admin.panel.bookings.show', $booking->book_id)->with('success', __('Booking status updated to :status.', ['status' => __($newStatus)]));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('hotel_admin.panel.bookings.show', $booking->book_id)->with('error', __('Failed to update booking status: ') . $e->getMessage());
        }
    }
}