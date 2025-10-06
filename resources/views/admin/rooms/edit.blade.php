@extends('layouts.admin')
@section('title','Sửa phòng')
@section('content')
<div class="max-w-4xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">
    Sửa phòng — {{ $room->room_number }} ({{ $room->type?->name }})
  </h1>

  @if(session('ok'))
  <div class="mb-4 rounded bg-green-50 border border-green-200 p-3 text-green-700">
    {{ session('ok') }}
  </div>
  @endif

  {{-- FORM THÔNG TIN PHÒNG --}}
  <form method="POST" action="{{ route('admin.rooms.update',$room) }}" class="space-y-5">
    @csrf @method('PUT')

    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block mb-1 font-medium">Số phòng <span class="text-red-600">*</span></label>
        <input name="room_number" value="{{ old('room_number',$room->room_number) }}" class="w-full border rounded p-2">
        @error('room_number') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block mb-1 font-medium">Loại phòng</label>
        <select name="room_type_id" class="w-full border rounded p-2">
          @foreach($types as $t)
          <option value="{{ $t->id }}" @selected(old('room_type_id',$room->room_type_id)==$t->id)>{{ $t->name }}</option>
          @endforeach
        </select>
        @error('room_type_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
      <div>
        <label class="block mb-1 font-medium">Tầng</label>
        <input type="number" name="floor" value="{{ old('floor',$room->floor) }}" class="w-full border rounded p-2">
        @error('floor') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>
      <div>
        <label class="block mb-1 font-medium">Trạng thái</label>
        <select name="status" class="w-full border rounded p-2">
          @foreach(['available'=>'Trống','occupied'=>'Đang ở','cleaning'=>'Đang dọn','maintenance'=>'Bảo trì'] as $v=>$label)
          <option value="{{ $v }}" @selected(old('status',$room->status)==$v)>{{ $label }}</option>
          @endforeach
        </select>
        @error('status') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>
    </div>

    <div>
      <label class="block mb-1 font-medium">Ghi chú</label>
      <textarea name="notes" rows="3" class="w-full border rounded p-2">{{ old('notes',$room->notes) }}</textarea>
      @error('notes') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-3">
      <button class="px-4 py-2 bg-indigo-600 text-white rounded">Lưu</button>
      <a href="{{ route('admin.rooms.index',['room_type_id'=>$room->room_type_id]) }}" class="px-4 py-2 border rounded">Quay về danh sách</a>
    </div>
  </form>

  {{-- QUẢN LÝ ẢNH PHÒNG --}}
  <hr class="my-8">

  <h2 class="text-lg font-semibold mb-3">Ảnh phòng</h2>

  {{-- Upload thêm ảnh --}}
  <form method="POST" action="{{ route('admin.rooms.images.store',$room) }}" enctype="multipart/form-data" class="space-y-3">
    @csrf
    <input type="file" name="images[]" multiple accept="image/*" class="w-full border rounded p-2">
    @error('images') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    @error('images.*') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Tải thêm ảnh</button>
  </form>

  {{-- Danh sách ảnh hiện có --}}
  <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
    @forelse($room->images as $img)
    <div class="border rounded p-2">
      <img src="{{ $img->url ?? \Illuminate\Support\Facades\Storage::url($img->path) }}"
        alt="" class="w-full h-32 object-cover rounded">
      <div class="mt-2 flex items-center justify-between">
        <form method="POST" action="{{ route('admin.rooms.images.primary',[$room,$img]) }}">
          @csrf @method('PUT')
          <button class="text-xs px-2 py-1 border rounded {{ $img->is_primary ? 'bg-green-50' : '' }}">
            {{ $img->is_primary ? 'Ảnh chính' : 'Đặt ảnh chính' }}
          </button>
        </form>
        <form method="POST" action="{{ route('admin.rooms.images.destroy',[$room,$img]) }}"
          onsubmit="return confirm('Xoá ảnh này?');">
          @csrf @method('DELETE')
          <button class="text-xs px-2 py-1 border rounded text-red-600">Xoá</button>
        </form>
      </div>
    </div>
    @empty
    <p class="text-gray-500 text-sm">Chưa có ảnh.</p>
    @endforelse
  </div>
</div>
@endsection