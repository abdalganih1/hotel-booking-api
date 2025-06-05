<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    public function index(): \Illuminate\View\View
    {
        $totalPlatformRevenue = Transaction::where('reason', 'admin_commission')->sum('amount');
        $totalHotelCommissionsPaid = Transaction::where('reason', 'hotel_commission')->sum('amount');
        $totalUserDeposits = Transaction::where('reason', 'deposit')->sum('amount');
        $totalBookingPayments = Transaction::where('reason', 'booking_payment')->sum('amount');

        $recentTransactions = Transaction::with('user', 'booking.room.hotel')->latest()->take(10)->get(); // هنا التعديل

        return view('admin.financials.overview', compact(
            'totalPlatformRevenue',
            'totalHotelCommissionsPaid',
            'totalUserDeposits',
            'totalBookingPayments',
            'recentTransactions'
        ));
    }

    public function transactions(Request $request): \Illuminate\View\View
    {
        $query = Transaction::with('user', 'booking.room.hotel')->orderBy('transaction_date', 'desc'); // هنا التعديل

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('transaction_type', $request->type);
        }
        if ($request->filled('reason') && in_array($request->reason, ['deposit', 'booking_payment', 'booking_refund', 'hotel_commission', 'admin_commission', 'cancellation_fee', 'transfer'])) {
            $query->where('reason', $request->reason);
        }

        $transactions = $query->paginate(25);
        $users = User::orderBy('username')->get();

        return view('admin.financials.transactions', compact('transactions', 'users'));
    }
}