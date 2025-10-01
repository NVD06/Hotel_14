<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Payment #{{ $payment->id }}</h2></x-slot>

  <div class="p-6 max-w-3xl">
    <div class="bg-white rounded shadow p-4">
      <div><b>Booking:</b> #{{ $payment->booking_id }} — {{ $payment->booking?->customer?->full_name }}</div>
      <div class="mt-2"><b>Amount:</b> {{ number_format($payment->amount,0) }}</div>
      <div class="mt-2"><b>Method:</b> {{ $payment->method }}</div>
      <div class="mt-2"><b>Paid at:</b> {{ optional($payment->paid_at)->format('d/m/Y H:i') }}</div>
      <div class="mt-2"><b>Status:</b> {{ $payment->status }}</div>
      @if($payment->reference)
      <div class="mt-2"><b>Reference:</b> {{ $payment->reference }}</div>
      @endif
      @if($payment->notes)
      <div class="mt-2"><b>Notes:</b> {{ $payment->notes }}</div>
      @endif
    </div>

    <div class="mt-6">
      <a href="{{ route('payments.index') }}" class="text-blue-600 underline">← Back to list</a>
    </div>
  </div>
</x-app-layout>
