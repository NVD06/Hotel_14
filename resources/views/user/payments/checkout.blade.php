@extends('layouts.app')
@section('title','Thanh toán')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
  <a href="{{ url()->previous() }}" class="text-indigo-600 hover:underline">← Quay lại</a>

  <div class="mt-4 rounded-2xl border bg-white p-6 space-y-5">
    <div class="text-xl font-semibold">Xác nhận thanh toán</div>

    <div class="rounded-xl bg-gray-50 border p-4 text-sm">
      <div>Phòng #{{ $pb['room_number'] }} ({{ $pb['room_type_name'] }})</div>
      <div>Thời gian: <b>{{ \Carbon\Carbon::parse($pb['check_in_at'])->format('d/m/Y H:i') }}</b>
        → <b>{{ \Carbon\Carbon::parse($pb['check_out_at'])->format('d/m/Y H:i') }}</b></div>
      <div class="mt-2">Số đêm: <b>{{ $pb['nights'] }}</b></div>
      <div class="mt-1">Giá/đêm: <b>{{ number_format($pb['rate'],0,',','.') }} đ</b></div>
      <div class="mt-1">Tạm tính: <b>{{ number_format($pb['subtotal'],0,',','.') }} đ</b></div>
      <div class="mt-1 text-lg">Tổng tiền: <b>{{ number_format($pb['total'],0,',','.') }} đ</b></div>
    </div>

    <form method="POST" action="{{ route('user.checkout.pay') }}" class="space-y-4">
      @csrf
      <label class="block font-medium">Phương thức thanh toán</label>
      <select name="method" class="border rounded p-2">
        <option value="cash">Thanh toán</option>
        <option value="bank">Chuyển khoản</option>
        <option value="momo">Momo</option>
        <option value="vnpay">VNPAY</option>
        <option value="card">Thẻ</option>
      </select>

      <button class="rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 font-medium">
        Thanh toán & tạo đơn
      </button>
    </form>
  </div>
</div>
@endsection
