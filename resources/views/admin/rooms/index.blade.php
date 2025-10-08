@extends('layouts.admin')
@section('title','Phòng')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    @if(session('ok'))
    <div class="mb-4 rounded bg-green-50 border border-green-200 p-3 text-green-700">
        {{ session('ok') }}
    </div>
    @endif

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Danh sách phòng</h1>

        <!-- thêm phòng -->
        <!-- @if(($types ?? collect())->count())
      <div class="hidden md:flex items-center gap-2">
        @foreach($types as $t)
          <a href="{{ route('admin.rooms.create', ['room_type_id'=>$t->id]) }}"
             class="px-3 py-2 rounded border hover:bg-gray-50 text-sm">
            + Thêm {{ $t->name }}
          </a>
        @endforeach
      </div>
    @endif
  </div> -->
        @if(($types ?? collect())->count())
        @php $active = request('room_type_id'); @endphp
        <div class="mb-5">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.rooms.index') }}"
                    class="px-3 py-2 rounded border text-sm
                {{ $active ? 'hover:bg-gray-50' : 'bg-indigo-600 text-white border-indigo-600' }}">
                    Tất cả
                </a>

                @foreach($types as $t)
                <a href="{{ route('admin.rooms.index', ['room_type_id'=>$t->id]) }}"
                    class="px-3 py-2 rounded border text-sm
                  {{ (string)$active === (string)$t->id
                       ? 'bg-indigo-600 text-white border-indigo-600'
                       : 'hover:bg-gray-50' }}">
                    Danh sách {{ $t->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @php
    $STATUS_VI = [
    'available' => 'Trống',
    'occupied' => 'Đang ở',
    'cleaning' => 'Đang dọn',
    'maintenance' => 'Bảo trì',
    ];
    @endphp

    <div class="overflow-x-auto bg-white rounded-lg border">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">Số phòng</th>
                    <th class="p-3 text-left">Loại</th>
                    <th class="p-3 text-left">Tầng</th>
                    <th class="p-3 text-left">Trạng thái</th>
                    <th class="p-3 text-right">Thao tác</th>
                </tr>
            </thead> 
            <tbody>
                @forelse($rooms as $room)
                <tr class="border-t">
                    <td class="p-3 font-medium">{{ $room->room_number }}</td>
                    <td class="p-3">{{ $room->type?->name }}</td>
                    <td class="p-3">{{ $room->floor ?? '-' }}</td>
                    <td class="p-3">
                        {{ $STATUS_VI[strtolower($room->status ?? '')] ?? ($room->status ?? '—') }}
                    </td>
                    <td class="p-3 text-right">
                        <a href="{{ route('admin.rooms.edit',$room) }}" class="px-3 py-1 rounded border">Sửa</a>
                        <form action="{{ route('admin.rooms.destroy',$room) }}" method="POST" class="inline"
                            onsubmit="return confirm('Xoá phòng này?');">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 rounded border text-red-600">Xoá</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="p-3 text-center text-gray-500" colspan="5">Chưa có phòng</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $rooms->links() }}</div>
</div>
@endsection