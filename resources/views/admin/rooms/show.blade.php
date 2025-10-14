@extends('layouts.admin')

@section('title','Chi tiết phòng')

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">

  {{-- Header --}}
  <div class="flex items-start justify-between gap-4">
    <div>
      <h1 class="text-2xl font-semibold">
        Phòng {{ $room->room_number }}
        @if($room->type)
          — {{ $room->type->name }}
        @endif
      </h1>
      <div class="text-gray-600 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
        <span>Tầng: {{ $room->floor ?? '—' }}</span>
        <span>Trạng thái: {{ $room->status ?? '—' }}</span>
        @if(!empty($room->notes))
          <span class="inline-flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2"/></svg>
            Ghi chú: {{ $room->notes }}
          </span>
        @endif
      </div>
    </div>
    <!-- <div class="shrink-0 flex gap-3">
      <a href="{{ route('admin.rooms.edit',$room) }}" class="px-3 py-2 border rounded-lg hover:bg-gray-50">Sửa thông tin</a>
      <a href="{{ route('admin.rooms.index') }}" class="px-3 py-2 border rounded-lg hover:bg-gray-50">Quay lại</a>
    </div> -->
  </div>

  {{-- Ảnh phòng --}}
  @if($room->images?->count())
    <div class="rounded-2xl border bg-white">
      <div class="p-5 border-b">
        <h2 class="text-lg font-semibold">Hình ảnh</h2>
      </div>
      <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach($room->images as $img)
          <img
            src="{{ asset('storage/'.$img->path) }}"
            alt="Room image"
            class="w-full h-40 object-cover rounded-xl border">
        @endforeach
      </div>
    </div>
  @endif

  {{-- Lịch sử / các lần đặt phòng liên quan --}}
  <div class="rounded-2xl border bg-white overflow-hidden">
    <div class="p-5 border-b">
      <h2 class="text-lg font-semibold">Các lần đặt phòng</h2>
      <p class="text-sm text-gray-500">Bao gồm mã đặt phòng, khách, số điện thoại, ngày nhận/trả và trạng thái.</p>
    </div>

    <div class="p-5 overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="text-left text-gray-600">
          <tr class="border-b">
            <th class="py-2 pr-4">Booking Code</th>
            <th class="py-2 pr-4">Khách</th>
            <th class="py-2 pr-4">Điện thoại</th>
            <th class="py-2 pr-4">Check-in</th>
            <th class="py-2 pr-4">Check-out</th>
            <th class="py-2 pr-4">Trạng thái</th>
            <th class="py-2 pr-4 text-right">Tổng tạm tính</th>
          </tr>
        </thead>
        <tbody>
          @forelse($room->bookingItems as $bi)
            @php
              $bk = $bi->booking ?? null;

              // Lấy tên/điện thoại theo nhiều khả năng (schema khác nhau)
              $customerName = $bk?->customer_name
                              ?? $bk?->customer?->full_name
                              ?? $bk?->customer?->name
                              ?? '—';
              $customerPhone = $bk?->phone
                              ?? $bk?->customer?->phone
                              ?? null;

              // Ngày nhận/trả nằm ở bảng bookings
              $cin  = $bk?->check_in_date  ?? null;
              $cout = $bk?->check_out_date ?? null;

              $cinText  = $cin  ? \Illuminate\Support\Carbon::parse($cin)->format('d/m/Y')  : '—';
              $coutText = $cout ? \Illuminate\Support\Carbon::parse($cout)->format('d/m/Y') : '—';

              // Tổng tạm tính: ưu tiên trường subtotal của booking_items nếu có, fallback 0
              $subtotal = $bi->subtotal ?? 0;
            @endphp

            <tr class="border-b last:border-0">
              <td class="py-2 pr-4 font-mono">{{ $bk?->booking_code ?? '—' }}</td>
              <td class="py-2 pr-4">{{ $customerName }}</td>
              <td class="py-2 pr-4">{{ $customerPhone ?? '—' }}</td>
              <td class="py-2 pr-4">{{ $cinText }}</td>
              <td class="py-2 pr-4">{{ $coutText }}</td>
              <td class="py-2 pr-4">{{ $bk?->status ?? '—' }}</td>
              <td class="py-2 pr-4 text-right">{{ number_format($subtotal) }} đ</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-3 text-gray-500">Chưa có lượt đặt nào cho phòng này.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection
