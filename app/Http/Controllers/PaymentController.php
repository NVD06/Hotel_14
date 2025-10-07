<?php

namespace App\Http\Controllers;

use App\Models\{Booking, BookingItem, Customer, Payment, Room};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class PaymentController extends Controller
{
    // Hiển thị trang thanh toán từ session
    public function checkoutSession(Request $request)
    {
        $pb = Session::get('pending_booking');
        if (!$pb) {
            return redirect()->route('rooms')->withErrors(['err' => 'Không có đơn tạm để thanh toán.']);
        }

        // Lấy room & ảnh cover để show đẹp (không bắt buộc)
        $room = Room::with('images', 'type')->find($pb['room_id']);

        return view('user.payments.checkout', [
            'pb'   => $pb,
            'room' => $room,
        ]);
    }

    // Nhấn "Thanh toán" -> tạo Payment + Booking + BookingItem (trong 1 transaction)
    public function payAndCreate(Request $request)
    {
        $pb = Session::get('pending_booking');
        if (!$pb) {
            return redirect()->route('rooms')->withErrors(['err' => 'Phiên thanh toán đã hết hạn.']);
        }

        $data = $request->validate([
            'method' => ['required', 'in:cash,bank,momo,vnpay,card'],
        ], [], ['method' => 'Phương thức thanh toán']);

        // (An toàn) Kiểm tra trùng lịch lần nữa trước khi lưu
        $in  = Carbon::parse($pb['check_in_at']);
        $out = Carbon::parse($pb['check_out_at']);

        $overlap = BookingItem::where('room_id', $pb['room_id'])
            ->whereHas('booking', function ($q) use ($in, $out) {
                $q->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                    ->where(function ($qq) use ($in, $out) {
                        $qq->whereBetween('check_in_date', [$in->toDateString(), $out->copy()->subDay()->toDateString()])
                            ->orWhereBetween('check_out_date', [$in->copy()->addDay()->toDateString(), $out->toDateString()])
                            ->orWhere(function ($x) use ($in, $out) {
                                $x->where('check_in_date', '<=', $in->toDateString())
                                    ->where('check_out_date', '>=', $out->toDateString());
                            });
                    });
            })->exists();

        if ($overlap) {
            Session::forget('pending_booking');
            return redirect()->route('rooms')
                ->withErrors(['err' => 'Khoảng thời gian vừa bị đặt bởi khách khác. Vui lòng chọn lại.']);
        }

        $user = $request->user();

        // Tìm/ tạo Customer không dùng user_id (vì schema của bạn không có)
        $customer = null;
        if (Schema::hasColumn('customers', 'email') && $user?->email) {
            $customer = Customer::where('email', $user->email)->first();
        }
        if (!$customer) {
            $payload = [];
            if (Schema::hasColumn('customers', 'full_name')) $payload['full_name'] = $user->name ?? 'Khách hàng';
            if (Schema::hasColumn('customers', 'email'))     $payload['email']     = $user->email ?? null;
            $customer = Customer::create($payload);
        }

        $booking = DB::transaction(function () use ($pb, $customer, $user, $data) {
            // 1) Tạo booking
            $booking = Booking::create([
                'customer_id'   => $customer->id,
                'check_in_date' => $pb['check_in_date'],
                'check_out_date' => $pb['check_out_date'],
                'status'        => 'confirmed',     // sau thanh toán -> confirmed
                'created_by'    => $user?->id,
                'notes'         => 'Check-in ' . $pb['check_in_at'] . ' | Check-out ' . $pb['check_out_at'],
                'total_amount'  => $pb['total'],
            ]);

            // 2) Item
            BookingItem::create([
                'booking_id' => $booking->id,
                'room_id'    => $pb['room_id'],
                'rate'       => $pb['rate'],
                'nights'     => $pb['nights'],
                'tax'        => $pb['tax'],
                'subtotal'   => $pb['subtotal'],
            ]);
            $sum = (float) $booking->items()->sum('subtotal');
            $booking->forceFill(['total_amount' => $sum])->save();

            // 3) Payment (mock: đã thanh toán đủ)
            Payment::create([
                'booking_id' => $booking->id,
                'amount'     => $pb['total'],
                'method'     => $data['method'], // cash/bank/momo/vnpay/card
                'status'     => 'paid',
                'reference'  => 'PM' . now()->format('ymd') . Str::upper(Str::random(6)),
                'paid_at'    => now(),
                'notes'      => null,
            ]);

            return $booking;
        });

        // Clear session
        Session::forget('pending_booking');

        // Điều hướng về trang xác nhận như trước
        return redirect()->route('user.rooms.bookings.confirm', $booking)
            ->with('ok', 'Thanh toán thành công. Đơn đã được tạo!');
    }

    public function checkoutExisting(Booking $booking, Request $request)
    {
        // Chỉ cho chủ đơn truy cập
        $user = $request->user();
        $booking->load(['customer', 'items.room.type', 'payments']);
        if ($booking->customer?->email && $user?->email && $booking->customer->email !== $user->email) {
            abort(403);
        }

        $paid = (float) $booking->payments()->where('status', 'paid')->sum('amount');
        $remain = max(0, (float)$booking->total_amount - $paid);

        return view('user.payments.existing_checkout', [
            'booking' => $booking,
            'paid'    => $paid,
            'remain'  => $remain,
        ]);
    }

    public function payExisting(Booking $booking, Request $request)
    {
        $user = $request->user();
        $booking->load(['customer', 'payments']);

        if ($booking->customer?->email && $user?->email && $booking->customer->email !== $user->email) {
            abort(403);
        }

        $data = $request->validate([
            'method' => ['required', 'in:cash,bank,momo,vnpay,card'],
            'amount' => ['required', 'numeric', 'min:0'],
        ], [], [
            'method' => 'Phương thức thanh toán',
            'amount' => 'Số tiền',
        ]);

        $paid   = (float) $booking->payments()->where('status', 'paid')->sum('amount');
        $remain = max(0, (float)$booking->total_amount - $paid);

        if ((float)$data['amount'] <= 0 || (float)$data['amount'] > $remain) {
            return back()->withErrors(['amount' => 'Số tiền phải > 0 và ≤ số còn thiếu (' . number_format($remain, 0, ',', '.') . ' đ).'])->withInput();
        }

        DB::transaction(function () use ($booking, $data) {
            Payment::create([
                'booking_id' => $booking->id,
                'amount'     => (float)$data['amount'],
                'method'     => $data['method'],
                'status'     => 'paid',
                'reference'  => 'PM' . now()->format('ymd') . Str::upper(Str::random(6)),
                'paid_at'    => now(),
                'notes'      => null,
            ]);

            // Nếu đã trả đủ thì cập nhật trạng thái
            $paid = (float) $booking->payments()->where('status', 'paid')->sum('amount');
            if ($paid >= (float)$booking->total_amount) {
                $booking->update(['status' => 'confirmed']);
            }
        });

        return redirect()->route('user.cart')->with('ok', 'Thanh toán thành công.');
    }
}
