@extends('admin.layouts.app')

@section('title', isset($user) ? 'تعديل مستخدم' : 'إنشاء مستخدم جديد')

@section('content')
<div class="py-6">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">
        {{ isset($user) ? 'تعديل المستخدم: ' . $user->username : 'إنشاء مستخدم جديد' }}
    </h1>

    <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
        <form action="{{ isset($user) ? route('admin.panel.users.update', $user->user_id) : route('admin.panel.users.store') }}" method="POST">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="username" class="block text-sm font-medium text-gray-700">اسم المستخدم</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username ?? '') }}" required
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('username') border-red-500 @enderror">
                    @error('username') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور {{ isset($user) ? '(اتركها فارغة لعدم التغيير)' : '' }}</label>
                    <input type="password" name="password" id="password" {{ isset($user) ? '' : 'required' }}
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('password') border-red-500 @enderror">
                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="first_name" class="block text-sm font-medium text-gray-700">الاسم الأول</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name ?? '') }}"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div class="sm:col-span-3">
                    <label for="last_name" class="block text-sm font-medium text-gray-700">الاسم الأخير</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name ?? '') }}"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div class="sm:col-span-3">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                    <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('phone_number') border-red-500 @enderror">
                     @error('phone_number') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="role" class="block text-sm font-medium text-gray-700">الدور</label>
                    <select id="role" name="role" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md @error('role') border-red-500 @enderror">
                        <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>مستخدم عادي</option>
                        <option value="hotel_admin" {{ old('role', $user->role ?? '') == 'hotel_admin' ? 'selected' : '' }}>مسؤول فندق</option>
                        <option value="app_admin" {{ old('role', $user->role ?? '') == 'app_admin' ? 'selected' : '' }}>مدير تطبيق</option>
                    </select>
                    @error('role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- أضف باقي الحقول مثل address, gender, age إذا لزم الأمر --}}

            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                    <a href="{{ route('admin.panel.users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        إلغاء
                    </a>
                    <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ isset($user) ? 'تحديث' : 'حفظ' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection