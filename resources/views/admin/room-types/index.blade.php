@extends('layouts.admin')
@section('title','Chọn loại phòng')
@section('content')
<div class="max-w-6xl mx-auto p-6">

  @if(session('ok'))
  <div class="mb-4 rounded bg-green-50 border border-green-200 p-3 text-green-700">
    {{ session('ok') }}
  </div>
  @endif



  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h1 class="text-2xl font-semibold">Chọn loại phòng để thêm mới</h1>

    <div class="flex items-center gap-2">
      <a href="{{ route('admin.room-types.create') }}"
        class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
        + Thêm loại phòng
      </a>


    </div>
  </div>

  <div class="flex flex-col gap-4">
    @foreach($types as $t)
    <div class="rounded-2xl border bg-white p-5">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div class="text-center md:text-left flex-1">
          <div class="text-lg font-semibold">Phòng {{ $t->name }}</div>
          <div class="text-sm text-gray-500">
            Sức chứa: {{ $t->capacity }} — Giá cơ bản: {{ number_format($t->base_price,0,',','.') }}đ
          </div>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
          <a href="{{ route('admin.rooms.index', ['room_type_id' => $t->id]) }}"
            class="inline-flex items-center justify-center px-4 py-2 rounded-lg border hover:bg-gray-50 w-full sm:w-auto">
            Sửa phòng
          </a>
          <a href="{{ route('admin.rooms.create', ['room_type_id' => $t->id]) }}"
            class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 w-full sm:w-auto">
            + Thêm phòng
          </a>
        </div>

      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection