@extends('layouts.admin')
@section('title','Admin · Home')

@section('content')
@php
$rooms = \App\Models\Room::with('type')
->orderBy('room_type_id')->orderBy('floor')->orderBy('room_number')
->get();

$counts = [
'available' => $rooms->where('status','available')->count(),
'occupied' => $rooms->where('status','occupied')->count(),
'cleaning' => $rooms->where('status','cleaning')->count(),
'maintenance' => $rooms->where('status','maintenance')->count(),
];
@endphp

<div class="space-y-6">

  <!-- Thống kê nhanh -->
  <div class="overflow-x-auto">
    <div class="inline-flex gap-4 min-w-max">
      <div class="bg-white rounded-2xl border p-4 w-56 shrink-0">
        <div class="text-sm text-gray-500">Trống</div>
        <div class="mt-1 text-2xl font-semibold">{{ $counts['available'] }}</div>
      </div>
      <div class="bg-white rounded-2xl border p-4 w-56 shrink-0">
        <div class="text-sm text-gray-500">Đang ở</div>
        <div class="mt-1 text-2xl font-semibold">{{ $counts['occupied'] }}</div>
      </div>
      <div class="bg-white rounded-2xl border p-4 w-56 shrink-0">
        <div class="text-sm text-gray-500">Đang dọn</div>
        <div class="mt-1 text-2xl font-semibold">{{ $counts['cleaning'] }}</div>
      </div>
      <div class="bg-white rounded-2xl border p-4 w-56 shrink-0">
        <div class="text-sm text-gray-500">Bảo trì</div>
        <div class="mt-1 text-2xl font-semibold">{{ $counts['maintenance'] }}</div>
      </div>
    </div>
  </div>

  <!-- Danh sách phòng + trạng thái  -->
  <div class="bg-white rounded-2xl border p-6">
    <div class="text-base font-semibold mb-3">Phòng & tình trạng</div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="p-3 text-left">Số phòng</th>
            <th class="p-3 text-left">Loại</th>
            <th class="p-3 text-left">Tầng</th>
            <th class="p-3 text-left">Trạng thái</th>
            <th class="p-3 text-left">Thời gian</th>
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
              @php
              $colors = [
              'available'=>'bg-green-100 text-green-700',
              'occupied'=>'bg-amber-100 text-amber-700',
              'cleaning'=>'bg-blue-100 text-blue-700',
              'maintenance'=>'bg-red-100 text-red-700',
              ];
              $label = [
              'available'=>'Trống',
              'occupied'=>'Đang ở',
              'cleaning'=>'Đang dọn',
              'maintenance'=>'Bảo trì',
              ][$room->status] ?? $room->status;
              @endphp
              <span class="px-2 py-1 rounded text-xs {{ $colors[$room->status] ?? 'bg-gray-100 text-gray-700' }}">
                {{ $label }}
              </span>
            </td>
            <td class="p-3">
              @if($room->status === 'occupied')
              @if($room->occupied_since)

              từ {{ $room->occupied_since->format('H:i d/m') }}
              <span class="text-gray-500">({{ $room->occupied_since->diffForHumans() }})</span>
              @else

              <span class="text-gray-500">không rõ (chưa ghi thời gian)</span>
              @endif
              @else
              —
              @endif
            </td>
            <td class="p-3 text-right">
              <a href="{{ route('admin.rooms.edit',$room) }}" class="px-3 py-1 rounded border">Sửa</a>
            </td>
          </tr>
          @empty
          <tr>
            <td class="p-3 text-center text-gray-500" colspan="6">Chưa có phòng</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection