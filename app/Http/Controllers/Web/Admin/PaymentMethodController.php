<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of the payment methods.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = PaymentMethod::orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        $paymentMethods = $query->paginate(15);
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin.payment_methods.create');
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('payment_methods', 'name')],
            'description' => ['nullable', 'string'],
        ]);

        PaymentMethod::create($validatedData);

        return redirect()->route('admin.panel.payment-methods.index')->with('success', __('Payment method created successfully.'));
    }

    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod): \Illuminate\View\View
    {
        return view('admin.payment_methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod): \Illuminate\View\View
    {
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('payment_methods', 'name')->ignore($paymentMethod->id)],
            'description' => ['nullable', 'string'],
        ]);

        $paymentMethod->update($validatedData);

        return redirect()->route('admin.panel.payment-methods.index')->with('success', __('Payment method updated successfully.'));
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod): \Illuminate\Http\RedirectResponse
    {
        // Consider if it's used in user_payment_methods before deleting
        // If it's used, you might prevent deletion or disassociate it first.
        if ($paymentMethod->userPaymentMethods()->exists()) {
            return redirect()->route('admin.panel.payment-methods.index')->with('error', __('Cannot delete payment method that is linked to users.'));
        }

        $paymentMethod->delete();
        return redirect()->route('admin.panel.payment-methods.index')->with('success', __('Payment method deleted successfully.'));
    }
}