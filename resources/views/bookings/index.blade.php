<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Bookings</h2></x-slot>
  <div class="p-6">
    <a href="{{ route('bookings.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">+ New booking</a>
    @if(session('ok'))<div class="mt-3 text-green-700">{{ session('ok') }}</div>@endif
    <table class="mt-4 w-full border">
      <thead><tr class="bg-gray-100">
        <th class="p-2 border">Code</th><th class="p-2 border">Customer</th>
        <th class="p-2 border">Check-in/out</th><th class="p-2 border">Total</th><th class="p-2 border">Status</th><th class="p-2 border"></th>
      </tr></thead>
      <tbody>
      @foreach($bookings as $b)
        <tr>
          <td class="p-2 border">{{ $b->booking_code }}</td>
          <td class="p-2 border">{{ $b->customer->full_name }}</td>
          <td class="p-2 border">{{ $b->check_in_date->format('d/m') }} → {{ $b->check_out_date->format('d/m') }}</td>
          <td class="p-2 border">{{ number_format($b->total_amount,0) }}</td>
          <td class="p-2 border">{{ $b->status }}</td>
          <td class="p-2 border text-right">
            <a href="{{ route('bookings.show',$b) }}" class="text-blue-600">View</a>
            <form action="{{ route('bookings.destroy',$b) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
              @csrf @method('DELETE') <button class="text-red-600 ml-2">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    <div class="mt-3">{{ $bookings->links() }}</div>
  </div>
</x-app-layout>
