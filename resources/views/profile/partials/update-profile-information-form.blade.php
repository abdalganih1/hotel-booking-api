<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('معلومات الملف الشخصي') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("قم بتحديث معلومات ملفك الشخصي وعنوان بريدك الإلكتروني.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Username --}}
        <div>
            <x-input-label for="username" :value="__('اسم المستخدم')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autofocus autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        {{-- First Name --}}
        <div>
            <x-input-label for="first_name" :value="__('الاسم الأول')" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" autocomplete="first-name" />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        {{-- Last Name --}}
        <div>
            <x-input-label for="last_name" :value="__('الاسم الأخير')" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" autocomplete="last-name" />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        {{-- Phone Number --}}
        <div>
            <x-input-label for="phone_number" :value="__('رقم الهاتف')" />
            <x-text-input id="phone_number" name="phone_number" type="tel" class="mt-1 block w-full" :value="old('phone_number', $user->phone_number)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        {{-- Address --}}
        <div>
            <x-input-label for="address" :value="__('العنوان')" />
            <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        {{-- Gender --}}
        <div>
            <x-input-label for="gender" :value="__('الجنس')" />
            <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">{{ __('اختر...') }}</option>
                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('ذكر') }}</option>
                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('أنثى') }}</option>
                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>{{ __('آخر') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>

        {{-- Age --}}
        <div>
            <x-input-label for="age" :value="__('العمر')" />
            <x-text-input id="age" name="age" type="number" class="mt-1 block w-full" :value="old('age', $user->age)" min="0" max="150" />
            <x-input-error class="mt-2" :messages="$errors->get('age')" />
        </div>


        {{-- Original Email Address (keep this for Breeze functionality) --}}
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('عنوان بريدك الإلكتروني غير مؤكد.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('انقر هنا لإعادة إرسال بريد التحقق.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('تم إرسال رابط تحقق جديد إلى عنوان بريدك الإلكتروني.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('حفظ') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('تم الحفظ.') }}</p>
            @endif
        </div>
    </form>
</section>