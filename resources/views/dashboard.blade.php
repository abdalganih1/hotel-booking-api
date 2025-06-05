<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-4">
                        {{ __('مرحبًا بك يا :username!', ['username' => Auth::user()->first_name ?? Auth::user()->username]) }}
                    </h3>

                    @if (Auth::user()->hasRole('app_admin'))
                        <p class="text-lg text-gray-700 mb-4">{{ __('أنت قمت بتسجيل الدخول كمدير تطبيق.') }}</p>
                        <div class="mt-4">
                            <a href="{{ route('admin.panel.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('الانتقال إلى لوحة تحكم المدير') }}
                            </a>
                        </div>
                    @elseif (Auth::user()->hasRole('hotel_admin'))
                        <p class="text-lg text-gray-700 mb-4">{{ __('أنت قمت بتسجيل الدخول كمسؤول فندق.') }}</p>
                        <div class="mt-4">
                            {{-- TODO: رابط لوحة تحكم مسؤول الفندق هنا إذا كان لديك واحدة --}}
                            <a href="#" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('إدارة فندقي') }}
                            </a>
                            <a href="#" class="inline-flex items-center px-4 py-2 ml-4 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('عرض حجوزات الفندق') }}
                            </a>
                        </div>
                    @else {{-- Regular User --}}
                        <p class="text-lg text-gray-700 mb-4">{{ __('أنت قمت بتسجيل الدخول كمستخدم عادي.') }}</p>
                        <div class="mt-4">
                            {{-- TODO: رابط لصفحة عرض الفنادق أو الحجوزات الشخصية --}}
                            <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('البحث عن الفنادق') }}
                            </a>
                            <a href="#" class="inline-flex items-center px-4 py-2 ml-4 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('عرض حجوزاتي') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>