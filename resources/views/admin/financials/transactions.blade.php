@extends('admin.layouts.app')

@section('title', __('All Transactions'))

@section('content')
<div class="py-6">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('All Transactions') }}</h1>

    <div class="bg-white shadow-md overflow-x-auto rounded-lg mb-6 p-4">
        <form method="GET" action="{{ route('admin.panel.financials.transactions') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div>
                <x-input-label for="user_id" :value="__('User')" />
                <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">{{ __('All Users') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="type" :value="__('Type')" />
                <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>{{ __('Credit') }}</option>
                    <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>{{ __('Debit') }}</option>
                </select>
            </div>
            <div>
                <x-input-label for="reason" :value="__('Reason')" />
                <select id="reason" name="reason" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">{{ __('All Reasons') }}</option>
                    @foreach(['deposit', 'booking_payment', 'booking_refund', 'hotel_commission', 'admin_commission', 'cancellation_fee', 'transfer'] as $r)
                        <option value="{{ $r }}" {{ request('reason') == $r ? 'selected' : '' }}>{{ __($r) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">{{ __('Filter') }}</button>
                <a href="{{ route('admin.panel.financials.transactions') }}" class="ml-2 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ __('Reset') }}</a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('ID') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Booking ID') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Reason') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->transaction_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->user->username ?? __('N/A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->booking_id ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($transaction->transaction_type == 'credit') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $transaction->transaction_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($transaction->amount, 2) }} {{ __('currency') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ __($transaction->reason) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->transaction_date->translatedFormat('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            {{ __('No transactions found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div class="mt-6">
        {{ $transactions->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection