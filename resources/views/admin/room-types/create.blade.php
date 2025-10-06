@extends('layouts.admin')
@section('title','Thêm loại phòng')

@section('content')
<div class="max-w-3xl mx-auto p-6">

  {{-- Flash message --}}
  @if(session('ok'))
    <div class="mb-4 rounded bg-green-50 border border-green-200 p-3 text-green-700">
      {{ session('ok') }}
    </div>
  @endif

  {{-- Lỗi validate tổng quát (nếu có) --}}
  @if ($errors->any())
    <div class="mb-4 rounded bg-red-50 border border-red-200 p-3 text-red-700">
      <div class="font-semibold mb-1">Vui lòng kiểm tra lại:</div>
      <ul class="list-disc pl-5 space-y-0.5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <h1 class="text-2xl font-semibold mb-6">Thêm loại phòng</h1>

  <form method="POST" action="{{ route('admin.room-types.store') }}" class="space-y-5">
    @csrf

    {{-- name --}}
    <div>
      <label for="name" class="block mb-1 font-medium">
        Tên loại phòng <span class="text-red-500">*</span>
      </label>
      <input id="name" type="text" name="name" value="{{ old('name') }}"
             class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring"
             maxlength="100" required placeholder="VD: Bình thường, Trung, Deluxe...">
      @error('name')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- capacity (int) --}}
    <div>
      <label for="capacity" class="block mb-1 font-medium">
        Sức chứa (người) <span class="text-red-500">*</span>
      </label>
      <input id="capacity" type="number" name="capacity" value="{{ old('capacity') }}"
             class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring"
             inputmode="numeric" min="1" step="1" required>
      @error('capacity')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- base_price (decimal) --}}
    <div>
      <label for="base_price" class="block mb-1 font-medium">
        Giá cơ bản (đ) <span class="text-red-500">*</span>
      </label>
      <input id="base_price" type="number" name="base_price" value="{{ old('base_price') }}"
             class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring"
             min="0" step="0.01" required placeholder="VD: 300000 hoặc 300000.00">
      @error('base_price')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- description (nullable) --}}
    <div>
      <label for="description" class="block mb-1 font-medium">Mô tả</label>
      <textarea id="description" name="description" rows="4"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring"
                maxlength="1000"
                placeholder="Phòng tiêu chuẩn, phòng trung cấp, ghi chú tiện ích...">{{ old('description') }}</textarea>
      @error('description')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center gap-3">
      <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
              onclick="this.disabled=true; this.form.submit();">
        Lưu loại phòng
      </button>
      <a href="{{ route('admin.room-types.index') }}"
         class="px-4 py-2 rounded-lg border hover:bg-gray-50">
        Hủy
      </a>
    </div>
  </form>
</div>
@endsection
