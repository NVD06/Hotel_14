@extends('layouts.app')
@section('title','Giỏ hàng của tôi')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
  <h1 class="text-2xl font-semibold mb-6">Giỏ hàng / Đơn của tôi</h1>

  @if(session('ok'))
    <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-700">{{ session('ok') }}</div>
  @endif

  @if(empty($bookings) || $bookings->count() === 0)
    <div class="text-gray-600">Chưa có đơn nào.</div>
  @else
    <div class="flex flex-col gap-4">
      @foreach($bookings as $b)
        @php
          $paid   = (float) $b->payments->where('status','paid')->sum('amount');
          $remain = max(0, (float)$b->total_amount - $paid);
        @endphp

        <div class="rounded-2xl border bg-white p-5">
          <div class="flex items-start justify-between gap-4">
            <div>
              <div class="text-lg font-semibold">Mã: {{ $b->booking_code ?? ('#'.$b->id) }}</div>
              <div class="text-gray-700">
                {{ \Carbon\Carbon::parse($b->check_in_date)->format('d/m/Y') }}
                → {{ \Carbon\Carbon::parse($b->check_out_date)->format('d/m/Y') }}
              </div>
              <div class="mt-2 text-sm text-gray-600">
                @foreach($b->items as $it)
                  Phòng #{{ $it->room?->room_number }} ({{ $it->room?->type?->name }})
                  — {{ $it->nights }} đêm x {{ number_format($it->rate,0,',','.') }} đ
                  = <b>{{ number_format($it->subtotal,0,',','.') }} đ</b><br>
                @endforeach
              </div>
            </div>

            <div class="text-right">
              <div>Tổng: <b>{{ number_format($b->total_amount ?: $b->items->sum('subtotal'),0,',','.') }} đ</b></div>
              <div>Đã trả: <b>{{ number_format($paid,0,',','.') }} đ</b></div>
              <div>Còn thiếu: <b class="{{ $remain>0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($remain,0,',','.') }} đ</b></div>
<!-- 
              <div class="mt-3 flex gap-2 justify-end">
                <a href="{{ route('user.cart.show', $b) }}"
                   class="rounded-lg border px-3 py-2 hover:bg-gray-50">Chi tiết</a>

                @if($remain > 0)
                  <a href="{{ route('user.cart.pay', $b) }}"
                     class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2">Thanh toán</a>
                @endif
              </div> -->
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-6">
      {{ $bookings->links() }}
    </div>
  @endif
</div>
@endsection
