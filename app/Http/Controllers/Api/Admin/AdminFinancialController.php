<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminFinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    /**
     * Display financial overview and aggregated reports.
     * @return \Illuminate\Http\JsonResponse
     */
    public function financialOverview()
    {
        $totalPlatformRevenue = Transaction::where('reason', 'admin_commission')->sum('amount');
        $totalHotelCommissionsPaid = Transaction::where('reason', 'hotel_commission')->sum('amount');
        $totalUserDeposits = Transaction::where('reason', 'deposit')->sum('amount');
        $totalBookingPayments = Transaction::where('reason', 'booking_payment')->sum('amount');
        $totalBookingRefunds = Transaction::where('reason', 'booking_refund')->sum('amount');

        // You can add more complex aggregates here, e.g., by date range, top hotels, etc.

        return response()->json([
            'total_platform_revenue' => number_format($totalPlatformRevenue, 2),
            'total_hotel_commissions_paid' => number_format($totalHotelCommissionsPaid, 2),
            'total_user_deposits' => number_format($totalUserDeposits, 2),
            'total_booking_payments' => number_format($totalBookingPayments, 2),
            'total_booking_refunds' => number_format($totalBookingRefunds, 2),
            'currency' => 'USD', // Define your currency
        ]);
    }

    /**
     * List all transactions in the system.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAllTransactions(Request $request)
    {
        $query = Transaction::with('user', 'booking.room.hotel');

        // Filtering by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        // Filtering by type (credit/debit)
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }
        // Filtering by reason
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }
        // Date range filtering (example)
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->latest('transaction_date')->paginate($request->get('limit', 25));
        return response()->json($transactions);
    }

    /**
     * Manually processes commissions for a confirmed booking.
     * This is usually automated, but an endpoint for review/correction.
     * @param Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function processCommissionsForBooking(Booking $booking)
    {
        if ($booking->booking_status !== 'confirmed') {
            return response()->json(['message' => 'Commissions can only be processed for confirmed bookings.'], 400);
        }

        DB::beginTransaction();
        try {
            $hotelAdmin = $booking->room->hotel->adminUser;
            $appAdmin = User::where('role', 'app_admin')->first(); // Assuming one primary app admin

            $processedMessages = [];

            // Process Hotel Commission (80%)
            if ($hotelAdmin) {
                $hotelCommissionExists = Transaction::where('booking_id', $booking->book_id)
                                                    ->where('reason', 'hotel_commission')
                                                    ->exists();
                if (!$hotelCommissionExists) {
                    Transaction::create([
                        'user_id' => $hotelAdmin->user_id,
                        'booking_id' => $booking->book_id,
                        'amount' => $booking->total_price * 0.80,
                        'transaction_type' => 'credit',
                        'reason' => 'hotel_commission',
                        'transaction_date' => now(),
                    ]);
                    $processedMessages[] = 'Hotel commission processed.';
                } else {
                    $processedMessages[] = 'Hotel commission already exists.';
                }
            } else {
                $processedMessages[] = 'No hotel admin assigned to the hotel for this booking. Hotel commission skipped.';
            }

            // Process App Admin Commission (20%)
            if ($appAdmin) {
                $adminCommissionExists = Transaction::where('booking_id', $booking->book_id)
                                                    ->where('reason', 'admin_commission')
                                                    ->exists();
                if (!$adminCommissionExists) {
                    Transaction::create([
                        'user_id' => $appAdmin->user_id,
                        'booking_id' => $booking->book_id,
                        'amount' => $booking->total_price * 0.20,
                        'transaction_type' => 'credit',
                        'reason' => 'admin_commission',
                        'transaction_date' => now(),
                    ]);
                    $processedMessages[] = 'App admin commission processed.';
                } else {
                    $processedMessages[] = 'App admin commission already exists.';
                }
            } else {
                $processedMessages[] = 'No app admin found. App admin commission skipped.';
            }

            DB::commit();
            return response()->json(['message' => 'Commissions processing complete.', 'details' => $processedMessages]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to process commissions: ' . $e->getMessage()], 500);
        }
    }
}