{{-- Debugging section - REMOVE LATER --}}
@if(config('app.debug'))
    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
        <h4 class="font-bold">Debug: photos_json from DB (raw)</h4>
        <pre class="text-xs overflow-x-auto">{{ $hotel->getRawOriginal('photos_json') }}</pre>
        <h4 class="font-bold mt-2">Debug: photos_json from DB (casted to array by Laravel)</h4>
        <pre class="text-xs overflow-x-auto">{{ print_r($hotel->photos_json, true) }}</pre>
        <h4 class="font-bold mt-2">Debug: photos_json passed to Alpine (encoded)</h4>
        <pre class="text-xs overflow-x-auto">{{ json_encode(old('photos', $hotel->photos_json ?? [])) }}</pre>
    </div>
@endif
{{-- End Debugging section --}}
@extends('admin.layouts.app')

@section('title', __('Edit Hotel: :name', ['name' => $hotel->name]))

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit Hotel: :name', ['name' => $hotel->name]) }}</h1>
            <a href="{{ route('admin.panel.hotels.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                → {{ __('Back to Hotels List') }}
            </a>
        </div>

        <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
            <form action="{{ route('admin.panel.hotels.update', $hotel->hotel_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="name" :value="__('Hotel Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $hotel->name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="rating" :value="__('Rating')" />
                        <x-text-input id="rating" name="rating" type="number" step="0.1" min="0" max="5" class="mt-1 block w-full" :value="old('rating', $hotel->rating)" />
                        <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="location" :value="__('Location')" />
                        <textarea id="location" name="location" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('location', $hotel->location) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('location')" />
                    </div>
                    <div>
                        <x-input-label for="contact_person_phone" :value="__('Contact Person Phone')" />
                        <x-text-input id="contact_person_phone" name="contact_person_phone" type="tel" class="mt-1 block w-full" :value="old('contact_person_phone', $hotel->contact_person_phone)" />
                        <x-input-error class="mt-2" :messages="$errors->get('contact_person_phone')" />
                    </div>
                    <div>
                        <x-input-label for="admin_user_id" :value="__('Hotel Admin (Optional)')" />
                        <select id="admin_user_id" name="admin_user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">{{ __('None') }}</option>
                            @foreach($hotelAdmins as $admin)
                                <option value="{{ $admin->user_id }}" {{ old('admin_user_id', $hotel->admin_user_id) == $admin->user_id ? 'selected' : '' }}>
                                    {{ $admin->username }} ({{ $admin->first_name }} {{ $admin->last_name }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('admin_user_id')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="notes" :value="__('Notes')" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $hotel->notes) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>

                    {{-- Photos Management - only URLs --}}
                    <div class="md:col-span-2" x-data="{
                        // Ensure photos is always a JavaScript array, even if old data is a JSON string or null
                        photos: JSON.parse('{{ json_encode(old('photos', $hotel->photos_json ?? [])) }}' || '[]'),
                        newPhotoUrlInput: ''
                    }">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ __('Photos') }}</h3>

                        {{-- Display and remove Photos --}}
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
                                    {{-- Hidden input to send photo URLs --}}
                                    <input type="hidden" :name="'photos[]'" :value="photoUrl">
                                </div>
                            </template>
                        </div>

                        {{-- Add Photo by URL input --}}
                        <div class="mb-4">
                            <x-input-label for="new_photo_url_input" :value="__('Add Photo by URL')" />
                            <div class="flex mt-1">
                                <x-text-input id="new_photo_url_input" x-model="newPhotoUrlInput" type="url" class="block w-full" placeholder="{{ __('Enter image URL') }}" />
                                <button type="button" @click="if(newPhotoUrlInput) { photos.push(newPhotoUrlInput); newPhotoUrlInput = ''; }"
                                        class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    {{ __('Add URL') }}
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ __('Enter URL and click "Add URL". Repeat for multiple URLs.') }}</p>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('photos')" />
                    </div>

                    {{-- Videos Management - only URLs --}}
                    <div class="md:col-span-2" x-data="{
                        // Ensure videos is always a JavaScript array, even if old data is a JSON string or null
                        videos: JSON.parse('{{ json_encode(old('videos', $hotel->videos_json ?? [])) }}' || '[]'),
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
                                    {{-- Hidden input to send video URLs --}}
                                    <input type="hidden" :name="'videos[]'" :value="videoUrl">
                                </div>
                            </template>
                        </div>

                        {{-- Add Video by URL input --}}
                        <div class="mb-4">
                            <x-input-label for="new_video_url_input" :value="__('Add Video by URL')" />
                            <div class="flex mt-1">
                                <x-text-input id="new_video_url_input" x-model="newVideoUrlInput" type="url" class="block w-full" placeholder="{{ __('Enter video URL') }}" />
                                <button type="button" @click="if(newVideoUrlInput) { videos.push(newVideoUrlInput); newVideoUrlInput = ''; }"
                                        class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    {{ __('Add URL') }}
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ __('Enter URL and click "Add URL". Repeat for multiple URLs.') }}</p>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('videos')" />
                    </div>

                </div>
                <div class="pt-8 flex justify-end">
                    <a href="{{ route('admin.panel.hotels.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Update Hotel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection