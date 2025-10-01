<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">Dashboard</h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <p>Chào {{ auth()->user()->name }}!</p>
        <!-- <div class="mt-4 flex gap-3">
          <a class="text-blue-600 underline" href="{{ route('customers.index') }}">Customers</a>
          <a class="text-blue-600 underline" href="{{ route('bookings.index') }}">Bookings</a>
          <a class="text-blue-600 underline" href="{{ route('payments.index') }}">Payments</a>
          @can('admin')
          <a class="text-blue-600 underline" href="{{ route('room-types.index') }}">Room Types</a>
          <a class="text-blue-600 underline" href="{{ route('rooms.index') }}">Rooms</a>
          @endcan
        </div> -->
      </div>
    </div>
  </div>
</x-app-layout>
