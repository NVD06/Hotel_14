@extends('layouts.admin')
@section('title','Admin · Home')

@section('content')
@php
use Illuminate\Support\Facades\DB;
use App\Models\Room;

$now = now();
$today = $now->toDateString();

// 1) Lấy danh sách phòng + loại
$rooms = Room::with('type')
->orderBy('room_type_id')
->orderBy('floor')
->orderBy('room_number')
->get();

// 2) Booking ĐANG Ở (active): check_in_date <= today < check_out_date
  $activeBookings=DB::table('booking_items as bi')
  ->join('bookings as b', 'b.id', '=', 'bi.booking_id')
  ->select([
  'bi.room_id',
  'b.check_in_date as cin',
  'b.check_out_date as cout',
  'b.status as bstatus',
  'b.customer_id',
  ])
  ->whereDate('b.check_in_date', '<=', $today)
    ->whereDate('b.check_out_date', '>', $today)
    ->whereNotIn('b.status', ['cancelled','no_show'])
    ->get()
    ->keyBy('room_id');

    // 3) Booking SẮP TỚI (next): check_in_date > today, lấy cái gần nhất cho mỗi phòng
    $nextRows = DB::table('booking_items as bi')
    ->join('bookings as b', 'b.id', '=', 'bi.booking_id')
    ->select([
    'bi.room_id',
    'b.check_in_date as cin',
    'b.check_out_date as cout',
    'b.status as bstatus',
    ])
    ->whereDate('b.check_in_date', '>', $today)
    ->whereNotIn('b.status', ['cancelled','no_show'])
    ->orderBy('b.check_in_date')
    ->get();

    $nextBookings = $nextRows
    ->groupBy('room_id')
    ->map(fn($g) => $g->first());

    // 4) Tính trạng thái hiển thị (display_status)
    $computed = $rooms->mapWithKeys(function ($room) use ($activeBookings) {
    // Ưu tiên trạng thái kỹ thuật
    if (in_array($room->status, ['maintenance','cleaning'])) {
    return [$room->id => ['display' => $room->status]];
    }
    // Có booking active → occupied, ngược lại available
    $occupied = $activeBookings->has($room->id);
    return [$room->id => ['display' => $occupied ? 'occupied' : 'available']];
    });

    // 5) Tổng đếm
    $counts = [
    'available' => $computed->where('display','available')->count(),
    'occupied' => $computed->where('display','occupied')->count(),
    'cleaning' => $computed->where('display','cleaning')->count(),
    'maintenance' => $computed->where('display','maintenance')->count(),
    ];

    // 6) Helper format ngày
    function viDate($d) { return \Carbon\Carbon::parse($d)->format('d/m/Y'); }
    @endphp

    <div class="max-w-[1600px] mx-auto px-4 py-8 space-y-8">

      {{-- SUMMARY CARDS --}}
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="rounded-xl border bg-white p-5">
          <div class="text-sm text-gray-500">Phòng trống</div>
          <div class="text-2xl font-semibold">{{ $counts['available'] }}</div>
        </div>
        <div class="rounded-xl border bg-white p-5">
          <div class="text-sm text-gray-500">Đang có khách</div>
          <div class="text-2xl font-semibold">{{ $counts['occupied'] }}</div>
        </div>
        <div class="rounded-xl border bg-white p-5">
          <div class="text-sm text-gray-500">Đang dọn</div>
          <div class="text-2xl font-semibold">{{ $counts['cleaning'] }}</div>
        </div>
        <div class="rounded-xl border bg-white p-5">
          <div class="text-sm text-gray-500">Bảo trì</div>
          <div class="text-2xl font-semibold">{{ $counts['maintenance'] }}</div>
        </div>
      </div>

      {{-- TABLE --}}
      <div class="rounded-2xl border bg-white overflow-hidden">
        <div class="px-5 py-4 border-b">
          <h2 class="text-lg font-semibold">Tình trạng phòng theo thời gian thực</h2>
          <div class="text-sm text-gray-500 mt-1">Thời điểm: {{ $now->format('d/m/Y H:i') }}</div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
              <tr class="text-left">
                <th class="p-3">Phòng</th>
                <th class="p-3">Loại</th>
                <th class="p-3">Tầng</th>
                <th class="p-3">Tình trạng</th>
                <th class="p-3">Đang ở</th>
                <th class="p-3">Sắp tới</th>
                <th class="p-3"></th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @forelse($rooms as $room)
              @php
              $state = $computed[$room->id]['display'] ?? 'available';
              $active = $activeBookings->get($room->id);
              $next = $nextBookings->get($room->id);

              $badgeClass = match($state) {
              'occupied' => ' text-red-700',
              'available' => ' text-green-700',
              'cleaning' => 'text-amber-700',
              'maintenance' => 'text-gray-800',
              default => 'text-gray-700'
              };

              @endphp
              <tr>
                <td class="p-3 font-medium">#{{ $room->room_number }}</td>
                <td class="p-3">{{ $room->type?->name }}</td>
                <td class="p-3">{{ $room->floor }}</td>

                <td class="p-3">
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full  {{ $badgeClass }}">
                    @if($state === 'occupied') Đang ở
                    @elseif($state === 'available') Trống
                    @elseif($state === 'cleaning') Đang dọn
                    @elseif($state === 'maintenance') Bảo trì
                    @else ● {{ $state }}
                    @endif
                  </span>
                </td>

                <td class="p-3">
                  @if($active)
                  {{ viDate($active->cin) }} → {{ viDate($active->cout) }}
                  @else
                  <span class="text-gray-400">—</span>
                  @endif
                </td>

                <td class="p-3">
                  @if($next)
                  {{ viDate($next->cin) }} → {{ viDate($next->cout) }}
                  @else
                  <span class="text-gray-400">—</span>
                  @endif
                </td>

                <td class="p-3 text-right whitespace-nowrap">
                  <a href="{{ route('admin.rooms.show', $room) }}" class="px-3 py-1 rounded border hover:bg-gray-50">Chi tiết</a>
                </td>
              </tr>
              @empty
              <tr>
                <td class="p-3 text-center text-gray-500" colspan="7">Chưa có phòng</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
    @endsection