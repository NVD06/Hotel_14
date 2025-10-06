@extends('layouts.admin')
@section('title','Thêm phòng')
@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-xl font-semibold mb-4">
        Thêm phòng — {{ $type?->name ?? 'Không xác định' }}
    </h1>

    @if(!$type)
    <div class="mb-4 rounded bg-yellow-50 border border-yellow-200 p-3 text-yellow-800">
        Vui lòng quay lại trang <a class="underline" href="{{ route('admin.room-types.index') }}">chọn loại phòng</a>.
    </div>
    @else
    <form method="POST" action="{{ route('admin.rooms.store') }}" class="space-y-5" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="room_type_id" value="{{ $type->id }}">

        <div>
            <label class="block mb-1 font-medium">Số phòng <span class="text-red-600">*</span></label>
            <input name="room_number" value="{{ old('room_number') }}" class="w-full border rounded p-2">
            @error('room_number') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block mb-1 font-medium">Tầng</label>
                <input type="number" name="floor" value="{{ old('floor') }}" class="w-full border rounded p-2">
                @error('floor') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-1 font-medium">Trạng thái</label>
                <select name="status" class="w-full border rounded p-2">
                    @foreach(['available'=>'Trống','occupied'=>'Đang ở','cleaning'=>'Đang dọn','maintenance'=>'Bảo trì'] as $v=>$label)
                    <option value="{{ $v }}" @selected(old('status')==$v)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block mb-1 font-medium">Ghi chú</label>
            <textarea name="notes" rows="3" class="w-full border rounded p-2">{{ old('notes') }}</textarea>
            @error('notes') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <!-- ẢNH PHÒNG  -->
        <div>
            <label class="block mb-1 font-medium">Ảnh phòng</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full border rounded p-2">
            @error('images') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            @error('images.*') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>


        <div id="previews" class="grid grid-cols-3 gap-2 mt-2"></div>

        <div class="flex items-center gap-3 mt-3">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Lưu</button>
            <a href="{{ route('admin.room-types.index') }}" class="px-4 py-2 border rounded">Quay lại chọn loại</a>
        </div>

        <script>
            (function() {
                const input = document.querySelector('input[name="images[]"]');
                const wrap = document.getElementById('previews');

                input.addEventListener('change', e => {
                    wrap.innerHTML = '';
                    [...e.target.files].forEach(f => {
                        const img = document.createElement('img');
                        img.className = 'w-full h-24 object-cover rounded border'; // thumb nhỏ
                        img.src = URL.createObjectURL(f);
                        wrap.appendChild(img);
                    });
                });
            })();
        </script>


    </form>
    @endif
</div>
@endsection