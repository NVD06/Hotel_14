@extends('layouts.app')
@section('title','Xác nhận đặt phòng '.$booking->booking_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
  @if(session('ok'))
    <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-700">{{ session('ok') }}</div>
  @endif

  <div class="rounded-2xl border bg-white p-6 space-y-4">
    <div class="text-xl font-semibold">Mã đặt: {{ $booking->booking_code }}</div>
    <div>Thời gian: <b>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y') }}</b> → <b>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d/m/Y') }}</b></div>
    <div class="pt-2 border-t">
      @foreach($booking->items as $it)
        <div class="py-2">
          Phòng #{{ $it->room?->room_number }} ({{ $it->room?->type?->name }}) — {{ $it->nights }} đêm x {{ number_format($it->rate,0,',','.') }} đ = <b>{{ number_format($it->subtotal,0,',','.') }} đ</b>
        </div>
      @endforeach
    </div>
    <div class="text-right text-lg">Tổng tiền: <b>{{ number_format($booking->total_amount,0,',','.') }} đ</b></div>
  </div>
</div>
@endsection
