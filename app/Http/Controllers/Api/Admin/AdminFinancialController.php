<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Booking; // For commission calculations on confirmed bookings
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For aggregate queries

class AdminFinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    /**
     * Display financial overview/reports. (عرض التقارير والإحصائيات المالية)
     */
    public function financialOverview(Request $request)
    {
        $totalPlatformRevenue = Transaction::where('reason', 'admin_commission')->sum('amount');
        $totalHotelCommissionsPaid = Transaction::where('reason', 'hotel_commission')->sum('amount');
        $totalUserDeposits = Transaction::where('reason', 'deposit')->sum('amount');
        $totalBookingPayments = Transaction::where('reason', 'booking_payment')->sum('amount');

        // TODO: Add more detailed reports (e.g., revenue by period, top earning hotels)

        return response()->json([
            'total_platform_revenue' => $totalPlatformRevenue,
            'total_hotel_commissions_paid' => $totalHotelCommissionsPaid,
            'total_user_deposits' => $totalUserDeposits,
            'total_payments_for_bookings' => $totalBookingPayments,
        ]);
    }

    /**
     * List all transactions in the system.
     */
    public function listAllTransactions(Request $request)
    {
        // TODO: Filtering by user, type, reason, date range
        $transactions = Transaction::with('user', 'booking.hotel') // Eager load related data
                                    ->latest()
                                    ->paginate(20);
        // return TransactionCollection::collection($transactions); // If you have a resource
        return response()->json($transactions);
    }

    /**
     * Manually trigger commission for confirmed bookings if needed.
     * (إدارة الأرصدة والتحويلات المالية - 80%/20%)
     * هذا يجب أن يحدث تلقائيًا عند تأكيد الحجز، ولكن يمكن أن تكون هذه دالة للمراجعة أو التصحيح.
     */
    public function processCommissionsForBooking(Booking $booking)
    {
        if ($booking->booking_status !== 'confirmed') {
            return response()->json(['message' => 'يمكن فقط معالجة العمولات للحجوزات المؤكدة.'], 400);
        }

        // Check if commissions already processed for this booking to avoid duplicates
        $hotelCommissionExists = Transaction::where('booking_id', $booking->booking_id)
                                        ->where('reason', 'hotel_commission')
                                        ->exists();
        $adminCommissionExists = Transaction::where('booking_id', $booking->booking_id)
                                        ->where('reason', 'admin_commission')
                                        ->exists();

        if ($hotelCommissionExists && $adminCommissionExists) {
            return response()->json(['message' => 'تم بالفعل معالجة العمولات لهذا الحجز.'], 400);
        }

        $appAdmin = Auth::user(); // The current app admin
        $hotelAdmin = $booking->hotel->adminUser; // User model of the hotel admin

        DB::beginTransaction();
        try {
            $processedTransactions = [];
            if (!$hotelCommissionExists && $hotelAdmin) {
                $hotelComm = Transaction::create([
                    'user_id' => $hotelAdmin->user_id,
                    'transaction_type' => 'credit',
                    'amount' => $booking->total_price * 0.80,
                    'reason' => 'hotel_commission',
                    'booking_id' => $booking->booking_id,
                    'transaction_date' => now(),
                ]);
                $processedTransactions['hotel_commission'] = $hotelComm;
            }

            if (!$adminCommissionExists) {
                 // مدير التطبيق يمكن أن يكون هو نفسه الـ appAdmin الذي ينفذ العملية أو مدير رئيسي آخر
                $platformAdminUser = User::where('role', 'app_admin')->orderBy('user_id')->first(); // مثال لاختيار أول مدير تطبيق
                if($platformAdminUser){
                    $adminComm = Transaction::create([
                        'user_id' => $platformAdminUser->user_id,
                        'transaction_type' => 'credit',
                        'amount' => $booking->total_price * 0.20,
                        'reason' => 'admin_commission',
                        'booking_id' => $booking->booking_id,
                        'transaction_date' => now(),
                    ]);
                    $processedTransactions['admin_commission'] = $adminComm;
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'تم معالجة العمولات بنجاح.',
                'transactions' => $processedTransactions
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل في معالجة العمولات: ' . $e->getMessage()], 500);
        }
    }

    // TODO: Add functions for managing payment methods if needed via API
    // (إدارة معلومات الدفع) - إذا كان المقصود هو إدارة طرق الدفع المتاحة في النظام
    // مثل PaymentMethodController.php
}