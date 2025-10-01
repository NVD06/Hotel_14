<nav class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            {{-- Left: Brand + Primary menu --}}
            <div class="flex items-center gap-8">
                {{-- Brand --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    {{-- Đổi icon/ảnh logo của bạn ở đây nếu muốn --}}
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-indigo-600 text-white font-bold">H</span>
                    <span class="text-lg font-semibold tracking-tight">{{ config('app.name','Hotel') }}</span>
                </a>

                {{-- Menu --}}
                <div class="hidden md:flex items-center gap-4">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">Customers</x-nav-link>
                    <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">Bookings</x-nav-link>
                    <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">Payments</x-nav-link>

                    @can('admin')
                    <div class="h-5 w-px bg-gray-200 mx-1"></div>
                    <x-nav-link :href="route('room-types.index')" :active="request()->routeIs('room-types.*')">Room Types</x-nav-link>
                    <x-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')">Rooms</x-nav-link>
                    @endcan
                </div>
            </div>

            {{-- Right: user dropdown (giữ của Breeze) --}}
            <div class="hidden md:flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none">
                            <div class="mr-2 text-gray-500">Hi,</div>
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-gray-400 uppercase">
                            {{ Auth::user()->email }}
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    {{-- Mobile menu đơn giản (tuỳ ý) --}}
   

</nav>