@extends('hotel_admin.layouts.app')

@section('title', __('Hotel Admin Dashboard'))

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('Hotel Admin Dashboard') }}</h1>

        @if(!$hotel)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">{{ __('Warning!') }}</strong>
                <span class="block sm:inline">{{ __('Your hotel admin account is not assigned to any hotel. Please contact the app administrator.') }}</span>
            </div>
        @else
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Hotel: :hotel_name', ['hotel_name' => $hotel->name]) }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card: Total Rooms -->
                <a href="{{ route('hotel_admin.panel.rooms.index') }}" class="block bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ __('Total Rooms') }}
                                    </dt>
                                    <dd class="text-lg font-semibold text-gray-900">
                                        {{ $totalRooms ?? 'N/A' }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card: Total Bookings -->
                <a href="{{ route('hotel_admin.panel.bookings.index') }}" class="block bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ __('Total Bookings') }}
                                    </dt>
                                    <dd class="text-lg font-semibold text-gray-900">
                                        {{ $totalBookings ?? 'N/A' }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card: Pending Bookings -->
                <a href="{{ route('hotel_admin.panel.bookings.index', ['status' => 'pending_verification']) }}" class="block bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ __('Pending Bookings') }}
                                    </dt>
                                    <dd class="text-lg font-semibold text-gray-900">
                                        {{ $pendingBookings ?? 'N/A' }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card: Total Earnings -->
                <a href="{{ route('hotel_admin.panel.financials.index') }}" class="block bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-700 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.172-.879-1.172-2.303 0-3.182s2.913-.879 4.085 0A3.002 3.002 0 0012 9.75v9.75m-9-5.625h9" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ __('Total Earnings') }}
                                    </dt>
                                    <dd class="text-lg font-semibold text-gray-900">
                                        {{ number_format($totalEarnings ?? 0, 2) }} {{ __('currency') }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Hotel Quick Actions') }}</h2>
                    <ul class="space-y-2">
                        <li><a href="{{ route('hotel_admin.panel.hotel.edit') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit Hotel Information') }}</a></li>
                        <li><a href="{{ route('hotel_admin.panel.rooms.create') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Add New Room') }}</a></li>
                        <li><a href="{{ route('hotel_admin.panel.bookings.index', ['status' => 'pending_verification']) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Review Pending Bookings') }}</a></li>
                        <li><a href="{{ route('hotel_admin.panel.financials.index') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View My Earnings') }}</a></li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endsection