{{-- Debugging section - REMOVE LATER --}}
@if(config('app.debug') && false) {{-- Changed to false to hide by default --}}
    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
        <h4 class="font-bold">Debug: photos_json from DB (raw) for Show Page</h4>
        <pre class="text-xs overflow-x-auto">{{ $hotel->getRawOriginal('photos_json') }}</pre>
        <h4 class="font-bold mt-2">Debug: photos_json from DB (casted to array by Laravel) for Show Page</h4>
        <pre class="text-xs overflow-x-auto">{{ print_r($hotel->photos_json, true) }}</pre>
         <h4 class="font-bold mt-2">Debug: photos_json for Alpine Carousel (encoded)</h4>
        <pre class="text-xs overflow-x-auto">{{ json_encode($hotel->photos_json) }}</pre>
    </div>
@endif
{{-- End Debugging section --}}

@extends('admin.layouts.app')

@section('title', __('Hotel Details: :name', ['name' => $hotel->name]))

@section('content')
<div class="py-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Hotel Details') }}</h1>
            <a href="{{ route('admin.panel.hotels.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                → {{ __('Back to Hotels List') }}
            </a>
        </div>

        <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Hotel Information') }}
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Hotel Name') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->name }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Location') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->location ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Rating') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->rating ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Contact Person Phone') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->contact_person_phone ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Hotel Admin') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $hotel->adminUser->username ?? __('None assigned') }}
                            @if($hotel->adminUser) ({{ $hotel->adminUser->first_name }} {{ $hotel->adminUser->last_name }}) @endif
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->notes ?: '-' }}</dd>
                    </div>

                    {{-- Photos Carousel --}}
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Photos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if(is_array($hotel->photos_json) && count($hotel->photos_json) > 0)
                                <div x-data="{ activeSlide: 0, photos: {{ json_encode($hotel->photos_json) }} }" class="relative w-full max-w-lg mx-auto">
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
                            @if(is_array($hotel->videos_json) && count($hotel->videos_json) > 0)
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($hotel->videos_json as $video)
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
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->created_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated At') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->updated_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-8">
            <div class="flex justify-between items-center px-4 py-5 sm:px-6 border-b border-gray-200 bg-white shadow-md sm:rounded-t-lg">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Rooms in this Hotel') }}
                </h3>
                <a href="{{ route('admin.panel.hotels.rooms.create', $hotel->hotel_id) }}" class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Add Room') }}
                </a>
            </div>
            <div class="bg-white shadow-md overflow-x-auto sm:rounded-b-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Room ID') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Max Occupancy') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Price Per Night') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Services') }}</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($hotel->rooms as $room)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->room_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->max_occupancy }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($room->price_per_night, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($room->services, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('admin.panel.rooms.show', $room->room_id) }}" class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out ml-2">
                                        {{ __('View') }}
                                    </a>
                                    <a href="{{ route('admin.panel.rooms.edit', $room->room_id) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.panel.rooms.destroy', $room->room_id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this room?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ __('No rooms found for this hotel.') }}
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