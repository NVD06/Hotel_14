{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('title','Welcome')

@section('content')
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

        @guest
          <h1 class="text-xl font-semibold">Chào mừng đến với {{ config('app.name','Hotel') }}!</h1>
          <p class="mt-2 text-gray-600">Đây là trang chào mừng. Hãy đăng nhập để vào Dashboard cá nhân.</p>

          <div class="mt-4 flex items-center gap-3">
            <a href="{{ route('login') }}"
               class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:opacity-90">
              Đăng nhập
            </a>

            @if (Route::has('register'))
              <a href="{{ route('register') }}"
                 class="px-4 py-2 rounded-md bg-gray-100 text-gray-800 hover:bg-gray-200">
                Đăng ký
              </a>
            @endif
          </div>
        @endguest

        @auth
          <h1 class="text-xl font-semibold">Xin chào, {{ Auth::user()->name }}!</h1>
          <p class="mt-2 text-gray-600">Bạn đã đăng nhập. Tiếp tục vào trang Dashboard của bạn.</p>

          <div class="mt-4">
            <a href="{{ route('user.home') }}"
               class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:opacity-90">
              Vào Dashboard
            </a>
          </div>
        @endauth

      </div>
    </div>
  </div>
@endsection
