@extends('layouts.app')
@section('title', 'Đặt phòng #'.$room->room_number)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <a href="{{ url()->previous() }}" class="text-indigo-600 hover:underline">← Quay lại</a>

    <div class="mt-4 grid md:grid-cols-2 gap-8">
        {{-- Thông tin phòng --}}
        <div class="rounded-2xl border bg-white overflow-hidden">
            <div class="w-full h-56 bg-gray-100">
                @php $cover = $room->images->first(); @endphp
                @if($cover?->path)
                <img src="{{ asset('storage/'.$cover->path) }}" class="w-full h-full object-cover" alt="">
                @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">Chưa có ảnh</div>
                @endif
            </div>
            <div class="p-5 space-y-1">
                <div class="text-xl font-semibold">Phòng #{{ $room->room_number }}</div>
                <div class="text-gray-600">Loại: {{ $type->name ?? '—' }} • Sức chứa: {{ $type->capacity ?? '—' }}</div>
                <div>Giá cơ bản: <b>{{ number_format($type->base_price ?? 0,0,',','.') }} đ/đêm</b></div>
            </div>
        </div>

        {{-- Form chọn ngày --}}
        <div class="rounded-2xl border bg-white p-6">
            @if($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-red-700 text-sm">
                <ul class="list-disc pl-5 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="{{ route('user.rooms.book.review', $room) }}" class="space-y-5">

                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Ngày nhận phòng</label>
                        <input type="date" name="check_in_date" value="{{ old('check_in_date') }}" class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Ngày trả phòng</label>
                        <input type="date" name="check_out_date" value="{{ old('check_out_date') }}" class="w-full border rounded p-2">
                    </div>
                </div>

                <div class="rounded-xl bg-gray-50 border p-4 text-sm">
                    <div>Giá cơ bản: <b id="rate">{{ number_format($type->base_price ?? 0,0,',','.') }} đ/đêm</b></div>
                    <div class="mt-1">Số đêm: <b id="nights">0</b></div>
                    <div class="mt-1">Tạm tính: <b id="subtotal">0 đ</b></div>
                </div>

                <button class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white py-2 font-medium">
                    Xác nhận đặt phòng
                </button>
            </form>
        </div>
    </div>
</div>

<script>
  const inDate     = document.querySelector('input[name="check_in_date"]');
  const outDate    = document.querySelector('input[name="check_out_date"]');
  const nightsEl   = document.getElementById('nights');
  const subtotalEl = document.getElementById('subtotal');
  const rate = parseInt({{ $type->base_price ?? 0 }});

  function fmt(n){ return new Intl.NumberFormat('vi-VN').format(n) + ' đ'; }

  function setMinOut(){
    if (!inDate?.valueAsDate) return;
    const a = inDate.valueAsDate; // Date
    const min = new Date(a.getFullYear(), a.getMonth(), a.getDate() + 1);
    const yyyy = min.getFullYear();
    const mm = String(min.getMonth() + 1).padStart(2,'0');
    const dd = String(min.getDate()).padStart(2,'0');
    outDate.min = `${yyyy}-${mm}-${dd}`;
    if (outDate.value && outDate.value < outDate.min) {
      outDate.value = outDate.min;
    }
  }

  function recalc(){
    const a = inDate?.valueAsDate || null;
    const b = outDate?.valueAsDate || null;

    let nights = 0;
    if (a && b) {
      const diffDays = (b - a) / 86400000; // 1000*60*60*24
      nights = Math.max(0, Math.round(diffDays));
    }
    nightsEl.textContent   = nights;
    subtotalEl.textContent = fmt(nights * rate);
  }

  [inDate, outDate].forEach(el => {
    el?.addEventListener('input',  () => { setMinOut(); recalc(); });
    el?.addEventListener('change', () => { setMinOut(); recalc(); });
  });

  setMinOut();
  recalc();
</script>

@endsection