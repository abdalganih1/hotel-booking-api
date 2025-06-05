@extends('hotel_admin.layouts.app')

@section('title', __('No Hotel Assigned'))

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-8 rounded-lg shadow-md text-center" role="alert">
            <h1 class="text-2xl font-bold mb-4">{{ __('No Hotel Assigned') }}</h1>
            <p class="text-lg mb-6">{{ __('Your hotel admin account is currently not linked to any hotel in the system.') }}</p>
            <p class="mb-6">{{ __('Please contact the application administrator to assign your account to a hotel, or submit a new hotel request if you have not done so already.') }}</p>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('My Profile') }}
            </a>
            {{-- Optional: Link to submit hotel request if not already done --}}
            {{-- <a href="{{ route('api.hoteladminrequests.store') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-indigo-600 text-sm font-medium rounded-md text-indigo-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Submit New Hotel Request') }}
            </a> --}}
        </div>
    </div>
</div>
@endsection