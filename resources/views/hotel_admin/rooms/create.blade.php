@extends('hotel_admin.layouts.app')

@section('title', __('Add New Room to :hotel_name', ['hotel_name' => $hotel->name]))

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Add New Room to :hotel_name', ['hotel_name' => $hotel->name]) }}</h1>
            <a href="{{ route('hotel_admin.panel.rooms.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                → {{ __('Back to Rooms List') }}
            </a>
        </div>

        <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
            <form action="{{ route('hotel_admin.panel.rooms.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="max_occupancy" :value="__('Maximum Occupancy')" />
                        <x-text-input id="max_occupancy" name="max_occupancy" type="number" min="1" class="mt-1 block w-full" :value="old('max_occupancy')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('max_occupancy')" />
                    </div>
                    <div>
                        <x-input-label for="price_per_night" :value="__('Price Per Night (Currency)')" />
                        <x-text-input id="price_per_night" name="price_per_night" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price_per_night')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('price_per_night')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="services" :value="__('Services Offered')" />
                        <textarea id="services" name="services" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('services') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('services')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="notes" :value="__('Notes')" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>
                    <div>
                        <x-input-label for="payment_link" :value="__('Payment Link (Optional)')" />
                        <x-text-input id="payment_link" name="payment_link" type="url" class="mt-1 block w-full" :value="old('payment_link')" />
                        <x-input-error class="mt-2" :messages="$errors->get('payment_link')" />
                    </div>

                    {{-- Photos Management - only URLs --}}
                    <div class="md:col-span-2" x-data="{
                        photos: {{ json_encode(old('photos', [])) }},
                        newPhotoUrlInput: ''
                    }">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ __('Photos') }}</h3>
                        <div class="flex flex-wrap gap-4 mb-4">
                            <template x-for="(photoUrl, index) in photos" :key="index">
                                <div class="relative w-32 h-32 rounded-md overflow-hidden shadow-md group">
                                    <img :src="photoUrl" class="w-full h-full object-cover">
                                    <button type="button" @click="photos.splice(index, 1)"
                                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <input type="hidden" :name="'photos[]'" :value="photoUrl">
                                </div>
                            </template>
                        </div>
                        <div class="mb-4">
                            <x-input-label for="new_photo_url_input" :value="__('Add Photo by URL')" />
                            <div class="flex mt-1">
                                <x-text-input id="new_photo_url_input" x-model="newPhotoUrlInput" type="url" class="block w-full" placeholder="{{ __('Enter image URL') }}" />
                                <button type="button" @click="if(newPhotoUrlInput && photos.indexOf(newPhotoUrlInput) === -1) { photos.push(newPhotoUrlInput); newPhotoUrlInput = ''; }"
                                        class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    {{ __('Add URL') }}
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ __('Enter URL and click "Add URL". Repeat for multiple URLs. Duplicate URLs will be ignored.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('photos')" />
                            <x-input-error class="mt-2" :messages="$errors->get('photos.*')" />
                        </div>
                    </div>

                    {{-- Videos Management - only URLs --}}
                    <div class="md:col-span-2" x-data="{
                        videos: {{ json_encode(old('videos', [])) }},
                        newVideoUrlInput: ''
                    }">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ __('Videos') }}</h3>
                        <div class="space-y-2 mb-4">
                            <template x-for="(videoUrl, index) in videos" :key="index">
                                <div class="flex items-center gap-2 mb-2 p-2 border rounded-md group">
                                    <span class="flex-grow text-sm text-gray-700 truncate" x-text="videoUrl"></span>
                                    <button type="button" @click="videos.splice(index, 1)"
                                            class="px-2 py-1 bg-red-600 text-white rounded-md text-xs hover:bg-red-700">
                                        {{ __('Remove') }}
                                    </button>
                                    <input type="hidden" :name="'videos[]'" :value="videoUrl">
                                </div>
                            </template>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="new_video_url_input" :value="__('Add Video by URL')" />
                            <div class="flex mt-1">
                                <x-text-input id="new_video_url_input" x-model="newVideoUrlInput" type="url" class="block w-full" placeholder="{{ __('Enter video URL') }}" />
                                <button type="button" @click="if(newVideoUrlInput && videos.indexOf(newVideoUrlInput) === -1) { videos.push(newVideoUrlInput); newVideoUrlInput = ''; }"
                                        class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    {{ __('Add URL') }}
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ __('Enter URL and click "Add URL". Repeat for multiple URLs. Duplicate URLs will be ignored.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('videos')" />
                            <x-input-error class="mt-2" :messages="$errors->get('videos.*')" />
                        </div>
                    </div>

                </div>
                <div class="pt-8 flex justify-end">
                    <a href="{{ route('hotel_admin.panel.rooms.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Add Room') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection