@extends('layouts.app')

@section('title','Các loại phòng')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 py-10">
  <h1 class="text-2xl md:text-3xl font-semibold mb-6">Các loại phòng</h1>

  <div class="flex flex-row flex-wrap justify-start items-start content-start gap-6">
    @forelse($types as $t)
    @php
    $room = $t->rooms->first(); // đã eager-load trong controller
    $cover = $room?->images->first(); // có thể null
    @endphp


    <a href="{{ route('user.rooms.byType', $t->id) }}"
      class="block shrink-0"
      style="width:350px;height:400px">

      <div class="rounded-2xl border bg-white shadow-sm hover:shadow-md transition overflow-hidden flex flex-col"
        style="width:350px;height:400px">

        <!-- Tên loại phòng (căn giữa) -->
        <div class="px-4 pt-4 pb-2 text-center">
          <h3 class="text-lg font-semibold truncate">{{ $t->name }}</h3>
        </div>

        <!-- Ảnh mô tả: chiều cao cố định để mọi card bằng nhau  -->
        <div class="w-full" style="height:190px">
          @if($cover?->path)
          <img src="{{ asset('storage/'.$cover->path) }}"
            alt="Loại phòng {{ $t->name }}"
            class="w-full h-full object-cover">
          @else
          <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
            Chưa có ảnh
          </div>
          @endif
        </div>

        <!-- Thông tin: chiếm phần còn lại  -->
        <div class="flex-1 px-4 py-3 flex flex-col overflow-hidden">
          @if(!empty($t->description))
          <p class="text-sm text-gray-600 line-clamp-2 text-center">
            {{ $t->description }}
          </p>
          @else
          <p class="text-sm text-gray-500 text-center">Mô tả sẽ được cập nhật.</p>
          @endif

          <div class="mt-3 grid grid-cols-2 gap-3 text-sm text-gray-700">
            <div class="text-center">
              <div class="text-gray-500">Sức chứa</div>
              <div class="font-medium">{{ $t->capacity ?? '—' }}</div>
            </div>
            <div class="text-center">
              <div class="text-gray-500">Số phòng</div>
              <div class="font-medium">{{ $t->rooms_count }}</div>
            </div>
            <div class="col-span-2 text-center">
              <div class="text-gray-500">Giá từ</div>
              <div class="font-semibold text-indigo-700">
                @isset($t->base_price)
                {{ number_format($t->base_price,0,',','.') }} đ/đêm
                @else
                —
                @endisset
              </div>
            </div>
          </div>
        </div>

      </div>
    </a>
    @empty
    <div class="text-gray-600">Hiện chưa có loại phòng nào.</div>
    @endforelse
  </div>

  <!-- Phân trang  -->
  @if($types instanceof \Illuminate\Contracts\Pagination\Paginator)
  <div class="mt-8">
    {{ $types->withQueryString()->links() }}
  </div>
  @endif
</div>
@endsection