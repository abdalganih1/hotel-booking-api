<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{--
        تحذير أمان: هذه الأزرار هي لأغراض التطوير والاختبار فقط.
        لا يجب استخدامها في بيئة الإنتاج أبدًا!
    --}}
    <div x-data="{ identifier: '{{ old('identifier', '') }}', password: '' }">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email/Username Address -->
            <div>
                <x-input-label for="identifier" :value="__('البريد الإلكتروني أو اسم المستخدم')" />
                <x-text-input id="identifier" class="block mt-1 w-full" type="text" name="identifier" x-model="identifier" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('identifier')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('كلمة المرور')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                x-model="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Auto-fill Buttons for Development -->
            <div class="mt-6 flex flex-col space-y-2">
                <button type="button" @click="identifier='admin@example.com'; password='password';"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('ملء بيانات مدير التطبيق') }}
                </button>
                <button type="button" @click="identifier='hoteladmin@example.com'; password='password';"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    {{ __('ملء بيانات مسؤول فندق') }}
                </button>
                 <button type="button" @click="identifier='regular_user1@example.com'; password='password';"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    {{ __('ملء بيانات مستخدم عادي') }}
                </button>
            </div>


            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('تذكرني') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('نسيت كلمة المرور؟') }}
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('تسجيل الدخول') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>