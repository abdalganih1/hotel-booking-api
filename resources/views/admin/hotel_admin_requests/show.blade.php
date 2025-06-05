@extends('admin.layouts.app')

@section('title', __('Review Hotel Admin Request - ID: :id', ['id' => $hotelAdminRequest->request_id]))

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">
                {{ __('Review Hotel Admin Request') }}
                <span class="text-lg font-normal text-gray-600">(ID: {{ $hotelAdminRequest->request_id }})</span>
            </h1>
            <a href="{{ route('admin.panel.hoteladminrequests.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                → {{ __('Back to Requests List') }}
            </a>
        </div>

        <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Request Details') }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ __('Submitted by:') }} {{ $hotelAdminRequest->user->username ?? __('Unknown') }} ({{ $hotelAdminRequest->user->first_name ?? '' }} {{ $hotelAdminRequest->user->last_name ?? '' }})
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Requested Hotel Name') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->requested_hotel_name }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Requested Hotel Location') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->requested_hotel_location ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Requested Contact Phone') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->requested_contact_phone ?: '-' }}</dd>
                    </div>
                     <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Request Notes') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->request_notes ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Request Date') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->created_at->translatedFormat('l, d F Y - H:i A') }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Current Status') }}</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($hotelAdminRequest->request_status == 'approved') bg-green-100 text-green-800
                                @elseif($hotelAdminRequest->request_status == 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ __($hotelAdminRequest->request_status) }}
                            </span>
                        </dd>
                    </div>
                     @if($hotelAdminRequest->reviewed_by_user_id)
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Reviewed By') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->reviewer->username ?? __('Unknown') }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Review Date') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->review_timestamp ? $hotelAdminRequest->review_timestamp->translatedFormat('l, d F Y - H:i A') : '-' }}</dd>
                    </div>
                    @endif
                    {{-- Added is_array() check for robustness --}}
                    @if(is_array($hotelAdminRequest->requested_photos_json) && count($hotelAdminRequest->requested_photos_json) > 0)
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Requested Photos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex flex-wrap gap-2">
                                @foreach($hotelAdminRequest->requested_photos_json as $photo)
                                    <img src="{{ $photo }}" alt="{{ __('Requested Hotel Photo') }}" class="h-24 w-24 object-cover rounded-md shadow-sm">
                                @endforeach
                            </div>
                        </dd>
                    </div>
                    @endif
                    {{-- Added is_array() check for robustness --}}
                    @if(is_array($hotelAdminRequest->requested_videos_json) && count($hotelAdminRequest->requested_videos_json) > 0)
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Requested Videos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul class="list-disc pl-5">
                                @foreach($hotelAdminRequest->requested_videos_json as $video)
                                    <li><a href="{{ $video }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $video }}</a></li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        @if($hotelAdminRequest->request_status == 'pending')
        <div class="mt-8 bg-white shadow-md sm:rounded-lg p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                {{ __('Take Action on Request') }}
            </h3>
            <form action="{{ route('admin.panel.hoteladminrequests.updatestatus', $hotelAdminRequest->request_id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <x-input-label for="status" :value="__('Change Status To:')" />
                        <select id="status" name="status" required class="mt-1 block w-full sm:w-1/2 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="approved">{{ __('Approve Request') }}</option>
                            <option value="rejected">{{ __('Reject Request') }}</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>
                    <div>
                        <x-input-label for="rejection_reason" :value="__('Rejection Reason (Optional)')" />
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('rejection_reason') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('rejection_reason')" />
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Update Request Status') }}
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="mt-8 bg-gray-50 p-4 rounded-md text-center">
            <p class="text-sm text-gray-600">
                {{ __('This request has already been reviewed and is :status.', ['status' => __($hotelAdminRequest->request_status)]) }}
            </p>
        </div>
        @endif
    </div>
</div>
@endsection