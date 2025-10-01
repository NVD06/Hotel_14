<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Booking #{{ $booking->booking_code }}</h2></x-slot>
  <div class="p-6">
    <div>Customer: <b>{{ $booking->customer->full_name }}</b></div>
    <div>Dates: {{ $booking->check_in_date->format('d/m/Y') }} → {{ $booking->check_out_date->format('d/m/Y') }}</div>
    <div>Status: {{ $booking->status }}</div>
    <div>Total: <b>{{ number_format($booking->total_amount,0) }}</b></div>

    <h3 class="font-semibold mt-4">Items</h3>
    <table class="w-full border mt-2">
      <thead><tr class="bg-gray-100"><th class="p-2 border">Room</th><th class="p-2 border">Rate</th><th class="p-2 border">Nights</th><th class="p-2 border">Tax</th><th class="p-2 border">Subtotal</th></tr></thead>
      <tbody>
      @foreach($booking->items as $it)
        <tr>
          <td class="p-2 border">#{{ $it->room_id }}</td>
          <td class="p-2 border">{{ number_format($it->rate,0) }}</td>
          <td class="p-2 border">{{ $it->nights }}</td>
          <td class="p-2 border">{{ number_format($it->tax,0) }}</td>
          <td class="p-2 border">{{ number_format($it->subtotal,0) }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</x-app-layout>
