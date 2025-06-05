@extends('admin.layouts.app')

@section('title', __('Main Dashboard'))

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('Main Dashboard') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card: Total Users -->
            <a href="{{ route('admin.panel.users.index') }}" class="block bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    {{ __('Total Users') }}
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $userCount ?? 'N/A' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Card: Total Hotels -->
            <a href="{{ route('admin.panel.hotels.index') }}" class="block bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    {{ __('Total Hotels') }}
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $hotelCount ?? 'N/A' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Card: Pending Bookings -->
            <a href="{{ route('admin.panel.bookings.index', ['status' => 'pending_verification']) }}" class="block bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
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

            <!-- Card: Total Platform Revenue -->
            <a href="{{ route('admin.panel.financials.overview') }}" class="block bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.172-.879-1.172-2.303 0-3.182s2.913-.879 4.085 0A3.002 3.002 0 0012 9.75v9.75m-9-5.625h9" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    {{ __('Total Platform Revenue') }}
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ number_format($totalRevenue ?? 0, 2) }} {{ __('currency') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- End of Cards -->

        {{-- Section for quick links or recent activities --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Quick Links') }}</h2>
                <ul class="space-y-2">
                    <li><a href="{{ route('admin.panel.hoteladminrequests.index') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Review Hotel Admin Requests') }}</a></li>
                    <li><a href="{{ route('admin.panel.financials.transactions') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View All Transactions') }}</a></li>
                    <li><a href="{{ route('admin.panel.payment-methods.index') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Manage Payment Methods') }}</a></li>
                    <li><a href="{{ route('admin.panel.faqs.index') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Manage FAQs') }}</a></li>
                </ul>
            </div>

            {{-- You can add a section for recent activities or a small chart here --}}
            {{-- <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Recent Activities') }}</h2>
                <p class="text-gray-600">No recent activities to display.</p>
            </div> --}}
        </div>

    </div>
@endsection