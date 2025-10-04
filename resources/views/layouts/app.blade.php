@vite(['resources/css/app.css','resources/js/app.js'])

<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('app.name','Laravel'))</title>
</head>

<body class="bg-gray-50 text-gray-900 antialiased">

 
  @includeIf('layouts.navigation')


  @hasSection('page_header')
  <header class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      @yield('page_header')
    </div>
  </header>
  @endif

  
  <main>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      @yield('content')
    </div>
  </main>
  @includeIf('layouts.partials.footer')
</body>

</html>