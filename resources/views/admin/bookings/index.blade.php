@extends('admin.layouts.app')

@section('title', __('Manage Bookings'))

@section('content')
<div class="py-6">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('All Bookings') }}</h1>

    <div class="bg-white shadow-md overflow-x-auto rounded-lg mb-6 p-4">
        <form method="GET" action="{{ route('admin.panel.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div>
                <x-input-label for="status" :value="__('Status')" />
                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>{{ __('Pending Verification') }}</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
            </div>
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
                <x-input-label for="hotel_id" :value="__('Hotel')" />
                <select id="hotel_id" name="hotel_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">{{ __('All Hotels') }}</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->hotel_id }}" {{ request('hotel_id') == $hotel->hotel_id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">{{ __('Filter') }}</button>
                <a href="{{ route('admin.panel.bookings.index') }}" class="ml-2 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ __('Reset') }}</a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Booking ID') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Hotel') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Room ID') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Check-in/out') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Total Price') }}</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($bookings as $booking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->book_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->user->username ?? __('N/A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->room->hotel->name ?? __('N/A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->room->room_id ?? __('N/A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($booking->booking_status == 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->booking_status == 'rejected' || $booking->booking_status == 'cancelled') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ __($booking->booking_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $booking->check_in_date->format('Y-m-d') }} - {{ $booking->check_out_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($booking->total_price, 2) }} {{ __('currency') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('admin.panel.bookings.show', $booking->book_id) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View Details') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            {{ __('No bookings found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="mt-6">
        {{ $bookings->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection