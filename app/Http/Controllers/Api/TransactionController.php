<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\PaymentMethod; // Ensure this is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the user's transactions and current balance.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $transactions = Transaction::where('user_id', $user->user_id)
                                    ->latest()
                                    ->paginate($request->get('limit', 15));

        // Calculate current balance on the fly
        $currentBalance = Transaction::where('user_id', $user->user_id)
                                    ->sum(DB::raw('CASE WHEN transaction_type = "credit" THEN amount ELSE -amount END'));

        return response()->json([
            'balance' => number_format($currentBalance, 2),
            'currency' => 'USD', // Or your local currency
            'transactions' => $transactions
        ]);
    }

    /**
     * Add funds to user's balance.
     * This method simulates adding funds. In a real application, this would integrate with a payment gateway.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFunds(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method_id' => ['required', Rule::exists('payment_methods', 'id')],
            // In a real app, payment gateway tokens/details would be here, not directly stored.
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Simulate successful payment gateway transaction
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'user_id' => $user->user_id,
                'amount' => $request->amount,
                'transaction_type' => 'credit',
                'reason' => 'deposit',
                'transaction_date' => now(),
                'booking_id' => null, // Not related to a booking
            ]);

            DB::commit();
            return response()->json(['message' => 'Funds added successfully (simulated).', 'transaction' => $transaction], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to add funds: ' . $e->getMessage()], 500);
        }
    }
}