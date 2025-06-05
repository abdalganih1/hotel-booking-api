@extends('admin.layouts.app')

@section('title', __('Edit User: :username', ['username' => $user->username]))

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit User: :username', ['username' => $user->username]) }}</h1>
            <a href="{{ route('admin.panel.users.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                → {{ __('Back to Users List') }}
            </a>
        </div>

        <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
            <form action="{{ route('admin.panel.users.update', $user->user_id) }}" method="POST">
                @csrf
                @method('PUT') {{-- مهم جداً لتحديد أن الطلب هو طلب تحديث (PUT/PATCH) --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="username" :value="__('Username')" />
                        <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('username')" />
                    </div>

                    <div>
                        <x-input-label for="role" :value="__('Role')" />
                        <select id="role" name="role" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md @error('role') border-red-500 @enderror">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>{{ __('Regular User') }}</option>
                            <option value="hotel_admin" {{ old('role', $user->role) == 'hotel_admin' ? 'selected' : '' }}>{{ __('Hotel Admin') }}</option>
                            <option value="app_admin" {{ old('role', $user->role) == 'app_admin' ? 'selected' : '' }}>{{ __('App Admin') }}</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role')" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password (Leave blank to keep current)')" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                    </div>

                    <div>
                        <x-input-label for="first_name" :value="__('First Name')" />
                        <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autocomplete="first-name" />
                        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                    </div>

                    <div>
                        <x-input-label for="last_name" :value="__('Last Name')" />
                        <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" autocomplete="last-name" />
                        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="phone_number" :value="__('Phone Number')" />
                        <x-text-input id="phone_number" name="phone_number" type="tel" class="mt-1 block w-full" :value="old('phone_number', $user->phone_number)" autocomplete="tel" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="address" :value="__('Address')" />
                        <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $user->address) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>

                    <div>
                        <x-input-label for="gender" :value="__('Gender')" />
                        <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">{{ __('Select...') }}</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                    </div>

                    <div>
                        <x-input-label for="age" :value="__('Age')" />
                        <x-text-input id="age" name="age" type="number" class="mt-1 block w-full" :value="old('age', $user->age)" min="0" max="150" />
                        <x-input-error class="mt-2" :messages="$errors->get('age')" />
                    </div>
                </div>

                <div class="pt-8 flex justify-end">
                    <a href="{{ route('admin.panel.users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Update User') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection