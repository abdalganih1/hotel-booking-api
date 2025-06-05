@extends('admin.layouts.app')

@section('title', __('Financial Overview'))

@section('content')
<div class="py-6">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('Financial Overview') }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Platform Revenue') }}</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalPlatformRevenue, 2) }} {{ __('currency') }}</dd>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Hotel Commissions Paid') }}</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalHotelCommissionsPaid, 2) }} {{ __('currency') }}</dd>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total User Deposits') }}</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalUserDeposits, 2) }} {{ __('currency') }}</dd>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Booking Payments') }}</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalBookingPayments, 2) }} {{ __('currency') }}</dd>
        </div>
    </div>

    <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Recent Transactions') }}</h2>
    <div class="bg-white shadow-md overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('ID') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Reason') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($recentTransactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->transaction_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->user->username ?? __('N/A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($transaction->transaction_type == 'credit') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $transaction->transaction_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($transaction->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ __($transaction->reason) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->transaction_date->translatedFormat('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            {{ __('No recent transactions.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 text-left">
        <a href="{{ route('admin.panel.financials.transactions') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
            {{ __('View All Transactions') }} →
        </a>
    </div>
</div>
@endsection