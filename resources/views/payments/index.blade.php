<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Payments</h2></x-slot>

  <div class="p-6">
    <a href="{{ route('payments.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">+ New payment</a>
    @if(session('ok'))<div class="mt-3 text-green-700">{{ session('ok') }}</div>@endif

    <table class="mt-4 w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Booking</th>
          <th class="p-2 border">Customer</th>
          <th class="p-2 border">Amount</th>
          <th class="p-2 border">Method</th>
          <th class="p-2 border">Paid at</th>
          <th class="p-2 border">Status</th>
          <th class="p-2 border"></th>
        </tr>
      </thead>
      <tbody>
      @foreach($payments as $p)
        <tr>
          <td class="p-2 border">{{ $p->id }}</td>
          <td class="p-2 border">#{{ $p->booking_id }}</td>
          <td class="p-2 border">{{ $p->booking?->customer?->full_name }}</td>
          <td class="p-2 border">{{ number_format($p->amount,0) }}</td>
          <td class="p-2 border">{{ $p->method }}</td>
          <td class="p-2 border">{{ optional($p->paid_at)->format('d/m/Y H:i') }}</td>
          <td class="p-2 border">{{ $p->status }}</td>
          <td class="p-2 border text-right">
            <a href="{{ route('payments.show',$p) }}" class="text-blue-600">View</a>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

    <div class="mt-3">{{ $payments->links() }}</div>
  </div>
</x-app-layout>
