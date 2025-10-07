@extends('layouts.app')

@section('title', 'Phòng loại: '.$type->name)

@section('content')
<div class="max-w-[1600px] mx-auto px-4 py-10">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl md:text-3xl font-semibold">Phòng loại: {{ $type->name }}</h1>
      <p class="text-gray-600 mt-1">
        @isset($type->capacity) Sức chứa: {{ $type->capacity }} — @endisset
        @isset($type->base_price) Giá từ: {{ number_format($type->base_price,0,',','.') }} đ/đêm @endisset
      </p>
    </div>
    <a href="{{ route('user.rooms.index') ?? route('rooms') }}"
      class="text-indigo-600 hover:underline">← Quay lại danh sách loại</a>
  </div>

  @if($rooms->count() === 0)
  <div class="text-gray-600">Hiện chưa có phòng nào thuộc loại này.</div>
  @endif

  {{-- Lưới card phòng: giữ kiểu 280×350 để đồng bộ với index --}}
  <div class="flex flex-row flex-wrap justify-start items-start content-start gap-6">
    @foreach($rooms as $r)
    @php $cover = $r->images->first(); @endphp

    <div class="rounded-2xl border bg-white shadow-sm hover:shadow-md transition overflow-hidden flex flex-col"
      style="width:280px;height:350px">

      <div class="px-4 pt-4 pb-2 text-center">
        <h3 class="text-lg font-semibold truncate">Phòng #{{ $r->room_number }}</h3>
        <div class="text-sm text-gray-500">
          Tầng {{ $r->floor ?? '—' }} @if(!empty($r->status)) • {{ $r->status }} @endif
        </div>
      </div>

      <div class="w-full" style="height:190px">
        @if($cover?->path)
        <img src="{{ asset('storage/'.$cover->path) }}"
          alt="Phòng #{{ $r->room_number }}"
          class="w-full h-full object-cover">
        @else
        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
          Chưa có ảnh
        </div>
        @endif
      </div>

      <div class="flex-1 px-4 py-3 flex flex-col">

        <div class="mt-auto">
          <a href="{{ route('user.rooms.book', $r) }}"
            class="w-full inline-flex justify-center rounded-xl border px-3 py-2 text-indigo-600 hover:bg-indigo-50">
            Đặt phòng
          </a>
        </div>

      </div>
    </div>
    @endforeach
  </div>

  <div class="mt-8">
    {{ $rooms->withQueryString()->links() }}
  </div>
</div>
@endsection