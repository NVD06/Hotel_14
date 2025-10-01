@vite(['resources/css/app.css','resources/js/app.js'])

<nav class="bg-white border-b">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">

      {{-- LEFT: Brand --}}
      <div class="flex items-center gap-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
          <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-indigo-600 text-white font-bold">H</span>
          <span class="text-lg font-semibold tracking-tight">{{ config('app.name','Hotel') }}</span>
        </a>
      </div>

      {{-- RIGHT: Primary menu + (Admin) + User dropdown --}}
      <div class="hidden md:flex items-center gap-6">

        {{-- Primary menu (user) --}}
        <div class="flex items-center gap-4">
          <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Home</x-nav-link>
          <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">About</x-nav-link>
          <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">Our room</x-nav-link>
          <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">Contact us</x-nav-link>

          {{-- Cart icon --}}
          <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" aria-label="Giỏ hàng">
            <i class="fa-solid fa-cart-shopping text-[18px]"></i>
            <span class="sr-only">Giỏ hàng</span>
          </x-nav-link>
        </div>

@can('admin')
  <div class="h-5 w-px bg-gray-200"></div>
  <a href="{{ route('admin.dashboard') }}"
     class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900 text-white hover:opacity-90">
     Admin
  </a>
@endcan
        {{-- User dropdown (giữ Breeze) --}}
        <div class="flex items-center">
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none">
                <div class="mr-2 text-gray-500">Hi,</div>
                <div>{{ Auth::user()->name }}</div>
                <div class="ml-1">
                  <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </button>
            </x-slot>

            <x-slot name="content">
              <div class="px-4 py-2 text-xs text-gray-400 uppercase">{{ Auth::user()->email }}</div>
              <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
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
  </div>

  {{-- Optional: Mobile menu của bạn để sau bổ sung --}}
</nav>
