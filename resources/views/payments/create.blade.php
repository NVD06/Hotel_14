<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">New Payment</h2></x-slot>

  <div class="p-6 max-w-xl">
    <form method="POST" action="{{ route('payments.store') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block mb-1">Booking</label>
        <select name="booking_id" class="border rounded p-2 w-full">
          @foreach($bookings as $b)
            <option value="{{ $b->id }}">#{{ $b->id }} — {{ $b->customer?->full_name }} (Total: {{ number_format($b->total_amount,0) }})</option>
          @endforeach
        </select>
        @error('booking_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block mb-1">Amount</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="border rounded p-2 w-full">
        @error('amount')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block mb-1">Method</label>
        <select name="method" class="border rounded p-2 w-full">
          @foreach(['cash','card','bank_transfer','ewallet'] as $m)
            <option value="{{ $m }}" @selected(old('method')==$m)>{{ $m }}</option>
          @endforeach
        </select>
        @error('method')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block mb-1">Paid at</label>
        <input type="datetime-local" name="paid_at" value="{{ old('paid_at') }}" class="border rounded p-2 w-full">
        @error('paid_at')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block mb-1">Reference</label>
        <input name="reference" value="{{ old('reference') }}" class="border rounded p-2 w-full">
      </div>

      <div>
        <label class="block mb-1">Status</label>
        <select name="status" class="border rounded p-2 w-full">
          @foreach(['paid','refunded','void'] as $s)
            <option value="{{ $s }}" @selected(old('status')==$s)>{{ $s }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block mb-1">Notes</label>
        <textarea name="notes" class="border rounded p-2 w-full">{{ old('notes') }}</textarea>
      </div>

      <button class="px-3 py-2 bg-blue-600 text-white rounded">Save</button>
      <a href="{{ route('payments.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
  </div>
</x-app-layout>
