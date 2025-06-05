<nav x-data="{ open: false }" class="bg-gray-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('hotel_admin.panel.dashboard') }}" class="font-semibold text-xl">
                    {{ __('Hotel Admin Panel') }}
                </a>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4 space-x-reverse">
                        <a href="{{ route('hotel_admin.panel.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('hotel_admin.panel.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">{{ __('Dashboard') }}</a>
                        <a href="{{ route('hotel_admin.panel.hotel.show') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('hotel_admin.panel.hotel.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">{{ __('My Hotel') }}</a>
                        <a href="{{ route('hotel_admin.panel.rooms.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('hotel_admin.panel.rooms.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">{{ __('Rooms') }}</a>
                        <a href="{{ route('hotel_admin.panel.bookings.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('hotel_admin.panel.bookings.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">{{ __('Bookings') }}</a>
                        <a href="{{ route('hotel_admin.panel.financials.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('hotel_admin.panel.financials.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">{{ __('Financials') }}</a>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                            {{ __('Logout') }} ({{ Auth::user()->username }})
                        </button>
                    </form>
                @endauth
            </div>
            <!-- Mobile menu button -->
            <div class="-mr-2 flex md:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white">
                    <svg :class="{'hidden': open, 'block': !open }" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg :class="{'hidden': !open, 'block': open }" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div :class="{'block': open, 'hidden': !open}" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('hotel_admin.panel.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('hotel_admin.panel.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">{{ __('Dashboard') }}</a>
            <a href="{{ route('hotel_admin.panel.hotel.show') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('hotel_admin.panel.hotel.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">{{ __('My Hotel') }}</a>
            <a href="{{ route('hotel_admin.panel.rooms.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('hotel_admin.panel.rooms.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">{{ __('Rooms') }}</a>
            <a href="{{ route('hotel_admin.panel.bookings.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('hotel_admin.panel.bookings.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">{{ __('Bookings') }}</a>
            <a href="{{ route('hotel_admin.panel.financials.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('hotel_admin.panel.financials.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">{{ __('Financials') }}</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-700">
            @auth
                <div class="flex items-center px-5">
                    <div>
                        <div class="text-base font-medium leading-none">{{ Auth::user()->username }}</div>
                        <div class="text-sm font-medium leading-none text-gray-400">{{ Auth::user()->role }}</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-right px-3 py-2 rounded-md text-base font-medium hover:bg-gray-700">
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</nav>