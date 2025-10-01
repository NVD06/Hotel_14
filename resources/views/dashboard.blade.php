<x-app-layout>
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <p>Chào {{ auth()->user()->name }}!</p>
        <p>Đây là trang dashboard. Bạn có thể tuỳ chỉnh nội dung ở đây.</p>
      </div>
    </div>
  </div>
</x-app-layout>