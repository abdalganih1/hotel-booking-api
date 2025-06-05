<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        // حماية دوال الإدارة (store, update, destroy) لدور مدير التطبيق فقط.
        // دالتي index و show يمكن أن تكونا متاحتين للجميع أو لمستخدمي API المصادق عليهم فقط.
        // في هذا التصميم، index ستكون عامة، و show ستكون أيضاً عامة (للمصادق عليهم).
        // دوال الإدارة الأخرى (store, update, destroy) يجب أن تكون محمية بـ 'role:app_admin'
        // وهذا يتم في ملف routes/api.php عبر مجموعة مسارات Admin.
    }

    /**
     * Display a listing of payment methods.
     * Accessible by public or any authenticated user.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = PaymentMethod::query();

        // Optional: filter by name or description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        $paymentMethods = $query->paginate($request->get('limit', 15)); // Add pagination
        return response()->json($paymentMethods);
    }

    /**
     * Store a newly created payment method.
     * Accessible only by 'app_admin' role (route protected).
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('payment_methods', 'name')],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $paymentMethod = PaymentMethod::create($validator->validated());

        return response()->json(['payment_method' => $paymentMethod, 'message' => 'Payment method created successfully.'], 201);
    }

    /**
     * Display the specified payment method.
     * Accessible by public or any authenticated user.
     * @param PaymentMethod $paymentMethod
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return response()->json($paymentMethod);
    }

    /**
     * Update the specified payment method.
     * Accessible only by 'app_admin' role (route protected).
     * @param Request $request
     * @param PaymentMethod $paymentMethod
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('payment_methods', 'name')->ignore($paymentMethod->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $paymentMethod->update($validator->validated());

        return response()->json(['payment_method' => $paymentMethod, 'message' => 'Payment method updated successfully.']);
    }

    /**
     * Remove the specified payment method.
     * Accessible only by 'app_admin' role (route protected).
     * @param PaymentMethod $paymentMethod
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Add a check if payment method is associated with any user_payment_methods
        // or if it's referenced in any completed transactions, if applicable.
        // For simplicity, directly delete:
        if ($paymentMethod->userPaymentMethods()->exists()) {
            return response()->json(['message' => 'Cannot delete payment method that is linked to users. Please unlink first.'], 400);
        }

        $paymentMethod->delete();
        return response()->json(['message' => 'Payment method deleted successfully.'], 204);
    }
}