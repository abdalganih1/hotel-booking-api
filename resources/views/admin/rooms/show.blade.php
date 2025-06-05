@extends('admin.layouts.app')

@section('title', __('Room Details: ID :id', ['id' => $room->room_id]))

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Room Details') }}</h1>
            <a href="{{ route('admin.panel.hotels.show', $room->hotel_id) }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                → {{ __('Back to :hotel_name Hotel Details', ['hotel_name' => $room->hotel->name ?? '']) }}
            </a>
        </div>

        <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Room Information') }}
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Room ID') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->room_id }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Hotel') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($room->hotel)
                                <a href="{{ route('admin.panel.hotels.show', $room->hotel->hotel_id) }}" class="text-indigo-600 hover:text-indigo-900">{{ $room->hotel->name }}</a>
                            @else
                                {{ __('N/A') }}
                            @endif
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Maximum Occupancy') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->max_occupancy }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Price Per Night') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($room->price_per_night, 2) }} {{ __('currency') }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Services Offered') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->services ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->notes ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Payment Link') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($room->payment_link)
                                <a href="{{ $room->payment_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $room->payment_link }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>

                    {{-- Photos Carousel (Copied from Hotel Show) --}}
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Photos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if(is_array($room->photos_json) && count($room->photos_json) > 0)
                                <div x-data="{ activeSlide: 0, photos: {{ json_encode($room->photos_json) }} }" class="relative w-full max-w-lg mx-auto">
                                    <div class="relative overflow-hidden rounded-lg shadow-lg h-64">
                                        <template x-for="(photo, index) in photos" :key="index">
                                            <div x-show="activeSlide === index"
                                                 x-transition:enter="transition ease-out duration-500"
                                                 x-transition:enter-start="opacity-0 transform scale-90"
                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                 x-transition:leave="transition ease-in duration-500"
                                                 x-transition:leave-start="opacity-100 transform scale-100"
                                                 x-transition:leave-end="opacity-0 transform scale-90"
                                                 class="absolute inset-0">
                                                <img :src="photo" class="w-full h-full object-cover">
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Navigation Buttons --}}
                                    <button type="button" @click="activeSlide = (activeSlide === 0) ? photos.length - 1 : activeSlide - 1"
                                            class="absolute top-1/2 left-2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button type="button" @click="activeSlide = (activeSlide === photos.length - 1) ? 0 : activeSlide + 1"
                                            class="absolute top-1/2 right-2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>

                                    {{-- Indicators --}}
                                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex space-x-2">
                                        <template x-for="(photo, index) in photos" :key="index">
                                            <button type="button" @click="activeSlide = index"
                                                    class="w-3 h-3 rounded-full"
                                                    :class="{ 'bg-white': activeSlide === index, 'bg-gray-400': activeSlide !== index }">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            @else
                                <p>-</p>
                            @endif
                        </dd>
                    </div>

                    {{-- Videos List --}}
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Videos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if(is_array($room->videos_json) && count($room->videos_json) > 0)
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($room->videos_json as $video)
                                        <li><a href="{{ $video }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $video }}</a></li>
                                    @endforeach
                                </ul>
                            @else
                                <p>-</p>
                            @endif
                        </dd>
                    </div>

                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Created At') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->created_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated At') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->updated_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-8">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-white shadow-md sm:rounded-t-lg">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Bookings for this Room') }}
                </h3>
            </div>
            <div class="bg-white shadow-md overflow-x-auto sm:rounded-b-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Booking ID') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Check-in Date') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Check-out Date') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Total Price') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($room->bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->book_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->user->username ?? __('N/A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($booking->booking_status == 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->booking_status == 'rejected' || $booking->booking_status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ __($booking->booking_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->check_in_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->check_out_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($booking->total_price, 2) }} {{ __('currency') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ __('No bookings found for this room.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection