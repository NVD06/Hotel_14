<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">New Booking</h2></x-slot>
  <div class="p-6 max-w-3xl">
    <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm" class="space-y-4">
      @csrf
      <div>
        <label>Customer</label>
        <select name="customer_id" class="border rounded p-2 w-full">
          @foreach($customers as $c)
            <option value="{{ $c->id }}">{{ $c->full_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div><label>Check in</label><input type="date" name="check_in_date" class="border rounded p-2 w-full"></div>
        <div><label>Check out</label><input type="date" name="check_out_date" class="border rounded p-2 w-full"></div>
      </div>

      <div class="mt-4">
        <h3 class="font-semibold mb-2">Items</h3>
        <div id="items"></div>
        <button type="button" id="addItem" class="mt-2 px-3 py-2 bg-gray-700 text-white rounded">+ Add room</button>
      </div>

      <div class="mt-4"><label>Notes</label><textarea name="notes" class="border rounded p-2 w-full"></textarea></div>
      <button class="px-3 py-2 bg-blue-600 text-white rounded">Save Booking</button>
    </form>
  </div>

  <script>
  const items = document.getElementById('items');
  document.getElementById('addItem').onclick = () => {
    const idx = items.children.length;
    const row = document.createElement('div');
    row.className = 'grid grid-cols-4 gap-2 mb-2';
    row.innerHTML = `
      <input class="border p-2 rounded col-span-1" name="items[${idx}][room_id]" placeholder="room_id">
      <input class="border p-2 rounded col-span-1" name="items[${idx}][rate]" type="number" step="0.01" placeholder="rate">
      <input class="border p-2 rounded col-span-1" name="items[${idx}][nights]" type="number" value="1">
      <input class="border p-2 rounded col-span-1" name="items[${idx}][tax]" type="number" step="0.01" value="0">
    `;
    items.appendChild(row);
  };
  </script>
</x-app-layout>
