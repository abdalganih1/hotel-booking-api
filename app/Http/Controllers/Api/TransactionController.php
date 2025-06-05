<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\TransactionCollection;
// use App\Http\Resources\UserResource; // لعرض معلومات المستخدم مع الرصيد

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the user's transactions and current balance.
     * (إدارة الرصيد الشخصي - عرض)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $transactions = Transaction::where('user_id', $user->user_id)
                                    ->latest()
                                    ->paginate(15);

        // حساب الرصيد الحالي (يمكن تحسين هذا بجعل الرصيد حقل في جدول المستخدمين يتم تحديثه)
        $credits = Transaction::where('user_id', $user->user_id)->where('transaction_type', 'credit')->sum('amount');
        $debits = Transaction::where('user_id', $user->user_id)->where('transaction_type', 'debit')->sum('amount');
        $currentBalance = $credits - $debits;

        return response()->json([
            'balance' => $currentBalance,
            'transactions' => $transactions // أو TransactionCollection
        ]);
    }

    /**
     * Add funds to user's balance. (إدارة الرصيد الشخصي - إضافة)
     * هذا يتطلب تكامل مع بوابة دفع. هنا مثال مبسط لإنشاء معاملة إيداع.
     */
    public function addFunds(Request $request)
    {
        $user = $request->user();
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'payment_method_id' => 'required|exists:payment_methods,payment_method_id', // مثال
            // ... (بيانات بوابة الدفع مثل رقم البطاقة إلخ، لكن هذا لا يجب تخزينه مباشرة)
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // TODO: هنا يتم منطق التكامل مع بوابة الدفع
        // بعد نجاح الدفع من البوابة:
        $transaction = Transaction::create([
            'user_id' => $user->user_id,
            'transaction_type' => 'credit',
            'amount' => $request->amount,
            'reason' => 'deposit',
            // 'booking_id' => null, // لا يوجد حجز مرتبط بالإيداع المباشر
            'transaction_date' => now(),
        ]);

        return response()->json([
            'message' => 'تم إضافة الرصيد بنجاح (محاكاة)',
            'transaction' => $transaction // أو TransactionResource
        ], 201);
    }
}