<footer class="bg-gray-100 text-black border-t-2 border-red-600">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">


    <div class="overflow-x-auto">
      <div class="flex flex-nowrap items-start justify-between gap-12 min-w-max">

        <!-- Cột 1: Contact -->
        <div class="min-w-[320px] flex-1">
          <h3 class="text-2xl font-semibold">Contact US</span></h3>
          <ul class="mt-6 space-y-4 text-gray-700">
            <li class="flex items-center gap-3"><i class="fa-solid fa-location-dot w-5 text-center"></i> Address</li>
            <li class="flex items-center gap-3"><i class="fa-solid fa-mobile-screen w-5 text-center"></i>
              <a href="tel:+011234569540" class="hover:underline text-black">461723461024612</a>
            </li>
            <li class="flex items-center gap-3"><i class="fa-regular fa-envelope w-5 text-center"></i>
              <a href="mailto:demo@gmail.com" class="hover:underline text-black">demo@gmail.com</a>
            </li>
          </ul>
        </div>

        <!-- Cột 2: Menu  -->
        <div class="min-w-[280px] flex-1">
          <h3 class="text-2xl font-semibold">Menu Link</span></h3>
          <ul class="mt-6 space-y-3 text-gray-700">
            <li><a class="hover:underline {{ request()->routeIs('user.home') ? 'text-red-600' : 'text-black' }}" href="{{ route('welcome') }}">Home</a></li>
            <li><a class="hover:underline {{ request()->routeIs('user.about')   ? 'text-red-600' : 'text-black' }}" href="{{ route('about') }}">About</a></li>
            <li><a class="hover:underline {{ request()->routeIs('user.rooms.index')   ? 'text-red-600' : 'text-black' }}" href="{{ route('user.rooms.index') }}">Our Room</a></li>
            <li><a class="hover:underline {{ request()->routeIs('user.contact') ? 'text-red-600' : 'text-black' }}" href="{{ route('contact') }}">Contact Us</a></li>
          </ul>
        </div>

        <!-- Cột 3: Newsletter -->
        <div class="min-w-[360px] flex-1">
          <h3 class="text-2xl font-semibold">News letter</span></h3>

          <!-- Mobile: 1 cột, Desktop: 1 hàng -->
          <form method="POST" action="#" class="mt-6 flex flex-col md:flex-row items-stretch gap-3">
            @csrf
            <input type="email" name="email" placeholder="Your email"
              class="h-11 w-full md:w-64 rounded-md bg-white text-black px-4 border border-gray-300">
            <button type="submit"
              class="h-11 w-full md:w-auto rounded-md bg-red-600 text-white font-semibold hover:bg-red-700">
              Subscribe
            </button>
          </form>

          <div class="mt-6 flex items-center gap-4 text-2xl text-black/80">
            <a href="#" aria-label="Facebook" class="hover:text-black"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" aria-label="Twitter" class="hover:text-black"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#" aria-label="LinkedIn" class="hover:text-black"><i class="fa-brands fa-linkedin-in"></i></a>
            <a href="#" aria-label="YouTube" class="hover:text-black"><i class="fa-brands fa-youtube"></i></a>
          </div>
        </div>

      </div>
    </div>

    <div class="mt-8 text-center text-xs text-gray-600 whitespace-nowrap">
      hehe đến đăng ký phòng đi mấy má
    </div>
  </div>
</footer>