@vite(['resources/css/app.css','resources/js/app.js'])

<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Admin')</title>
</head>
<body class="bg-gray-50 text-gray-900">

  {{-- Topbar gọn cho admin --}}
  <header class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-indigo-600 text-white font-bold">H</span>
        <span class="text-lg font-semibold tracking-tight">HOTTE</span>
      </a>
      <div class="flex items-center gap-4">
        <span class="text-sm text-gray-600">Hi, {{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">@csrf
          <button class="text-sm text-gray-600 hover:text-gray-900">Log out</button>
        </form>
      </div>
    </div>
  </header>

  {{-- Khung: sidebar trái + nội dung --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6 grid grid-cols-1 md:grid-cols-[220px_1fr] gap-6">
      {{-- SIDEBAR (chỉ admin) --}}
      <aside class="md:sticky md:top-[4.5rem] md:h-[calc(100vh-5rem)] md:overflow-y-auto">

        @php
          // CHỈ GIỮ MENU ADMIN – xếp dọc
          $menu = [
            ['label'=>'Trang chủ', 'route'=>'admin.dashboard',   'active'=>'admin.dashboard'],
            ['label'=>'Doanh thu', 'route'=>'admin.revenue',     'active'=>'admin.revenue'],
            ['label'=>'Phòng',     'route'=>'admin.rooms.index', 'active'=>'admin.rooms.*'],
            ['label'=>'Pages',     'route'=>'admin.pages.index', 'active'=>'admin.pages.*'],
          ];
        @endphp

        <nav class="bg-white border rounded-2xl p-3">
          <ul class="space-y-1">
            @foreach($menu as $it)
              @php $isActive = request()->routeIs($it['active']); @endphp
              <li>
                <a href="{{ route($it['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition
                          {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-medium' : 'hover:bg-gray-100' }}">
                  <span class="inline-block w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-indigo-600' : 'bg-gray-300' }}"></span>
                  <span>{{ $it['label'] }}</span>
                </a>
              </li>
            @endforeach
          </ul>
        </nav>
      </aside>

      {{-- NỘI DUNG --}}
      <main class="min-h-[60vh]">
        @yield('content')
      </main>
    </div>
  </div>
</body>
</html>
