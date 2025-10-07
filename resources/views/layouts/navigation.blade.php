<nav class="bg-white border-b">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">

      <!-- LEFT: Brand  -->
      <div class="flex items-center gap-3">
        <a href="{{ route('welcome') }}" class="flex items-center gap-2">
          <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-indigo-600 text-white font-bold">H</span>
          <span class="text-lg font-semibold tracking-tight">{{ config('app.name','Hotel') }}</span>
        </a>
      </div>

      <!-- RIGHT  -->
      <div class="hidden md:flex items-center gap-6">
        <div class="flex items-center gap-4">
          @auth
          <x-nav-link :href="route('user.home')" :active="request()->routeIs('user.home')">
            Home
          </x-nav-link>
          @else
          <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
            Home
          </x-nav-link>
          @endauth
          <x-nav-link :href="route('about')" :active="request()->routeIs('about')">About</x-nav-link>
          <x-nav-link :href="route('user.rooms.index')"
            :active="request()->routeIs('user.rooms.*')">
            Our room
          </x-nav-link>
          <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')">Contact us</x-nav-link>


          @auth
          <x-nav-link :href="route('user.cart')" :active="request()->routeIs('user.cart*')">
  <span class="inline-flex items-center gap-2">
    <svg width="16" height="16" ...>...</svg>
    Giỏ hàng
  </span>
</x-nav-link>
          @endauth
        </div>

        <!-- Admin  -->
        @can('admin')
        <div class="h-5 w-px bg-gray-200"></div>
        <a href="{{ route('admin.home') }}" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900 text-white hover:opacity-90">
          Admin
        </a>
        @endcan

        <!-- Guest -->
        @auth
        <div class="flex items-center">
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
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
              <div class="px-4 py-2 text-xs text-gray-400 uppercase">{{ Auth::user()->email }}</div>
              <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('profile.edit') }}">Profile</a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log out</button>
              </form>
            </x-slot>
          </x-dropdown>
        </div>
        @endauth

        @guest
        <div class="flex items-center gap-3">
          <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Log in</a>
          @if (Route::has('register'))
          <a href="{{ route('register') }}" class="text-sm text-white bg-indigo-600 px-3 py-1.5 rounded-md hover:opacity-90">Register</a>
          @endif
        </div>
        @endguest

      </div>
    </div>
  </div>
</nav>