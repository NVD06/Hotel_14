<?php

namespace App\Http\Controllers;

use App\Models\{Booking, BookingItem, Customer, RoomType, Room};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth, Session};
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'items.room', 'payments'])
            ->latest()
            ->paginate(12);

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $customers = Customer::orderBy('full_name')->get();
        $types     = RoomType::orderBy('name')->get();

        return view('bookings.create', compact('customers', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'     => ['required', 'exists:customers,id'],
            'check_in_date'   => ['required', 'date'],
            'check_out_date'  => ['required', 'date', 'after:check_in_date'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.room_id' => ['required', 'exists:rooms,id'],
            'items.*.rate'    => ['required', 'numeric', 'min:0'],
            'items.*.nights'  => ['required', 'integer', 'min:1'],
            'items.*.tax'     => ['nullable', 'numeric', 'min:0'],
            'notes'           => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {
            $booking = Booking::create([
                'customer_id'    => $data['customer_id'],
                'check_in_date'  => $data['check_in_date'],
                'check_out_date' => $data['check_out_date'],
                'status'         => 'confirmed',
                'created_by'     => Auth::id() ?? null,
                'notes'          => $data['notes'] ?? null,
                'total_amount'   => 0,
            ]);

            foreach ($data['items'] as $it) {
                $rate     = (float) $it['rate'];
                $nights   = (int)   $it['nights'];
                $tax      = isset($it['tax']) ? (float) $it['tax'] : 0.0;
                $subtotal = ($rate * $nights) + $tax;

                BookingItem::create([
                    'booking_id' => $booking->id,
                    'room_id'    => $it['room_id'],
                    'rate'       => $rate,
                    'nights'     => $nights,
                    'tax'        => $tax,
                    'subtotal'   => $subtotal,
                ]);
            }
            $booking->save();
        });

        return redirect()->route('bookings.index')->with('ok', 'Đặt phòng thành công');
    }
    public function show(Booking $booking)
    {
        $booking->load(['customer', 'items.room', 'payments', 'creator']);
        return view('bookings.show', compact('booking'));
    }
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('ok', 'Đã xoá booking');
    }
    public function book(Room $room)
    {
        $room->load(['type', 'images']);
        return view('user.rooms.book', [
            'room' => $room,
            'type' => $room->type,
        ]);
    }
    public function review(Room $room, Request $request)
    {
        $data = $request->validate([
            'check_in_date'   => ['required', 'date', 'after_or_equal:today'],
            'check_out_date'  => ['required', 'date', 'after:check_in_date'],
            'check_in_time'   => ['nullable', 'date_format:H:i'],
            'check_out_time'  => ['nullable', 'date_format:H:i'],
        ], [], [
            'check_in_date' => 'Ngày nhận phòng',
            'check_out_date' => 'Ngày trả phòng',
            'check_in_time' => 'Giờ nhận phòng',
            'check_out_time' => 'Giờ trả phòng',
        ]);

        $in  = Carbon::parse($data['check_in_date'] . ' ' . ($data['check_in_time'] ?? '00:00'))->startOfMinute();
        $out = Carbon::parse($data['check_out_date'] . ' ' . ($data['check_out_time'] ?? '00:00'))->startOfMinute();
        if ($out->lessThanOrEqualTo($in)) {
            return back()->withErrors(['check_out_date' => 'Thời gian trả phòng phải sau thời gian nhận phòng.'])->withInput();
        }
        // Tính số đêm (làm tròn lên)
        $nights = (int) ceil($in->floatDiffInRealHours($out) / 24);
        // Tính giá
        $room->load('type');
        $rate     = (float) ($room->type->base_price ?? 0);
        $tax      = 0;
        $subtotal = $rate * $nights + $tax;
        $total    = $subtotal;
        // Lưu “đơn tạm” vào session (chưa đụng DB)
        $payload = [
            'room_id'        => $room->id,
            'room_number'    => $room->room_number,
            'room_type_name' => $room->type?->name,
            'check_in_at'    => $in->toIso8601String(),
            'check_out_at'   => $out->toIso8601String(),
            'check_in_date'  => $in->toDateString(),
            'check_out_date' => $out->toDateString(),
            'nights'         => $nights,
            'rate'           => $rate,
            'tax'            => $tax,
            'subtotal'       => $subtotal,
            'total'          => $total,
        ];
        Session::put('pending_booking', $payload);
        // Chuyển sang trang thanh toán
        return redirect()->route('user.checkout');
    }
    public function confirm(Booking $booking)
    {
        $booking->load(['items.room.type', 'customer']);
        return view('user.rooms.confirm', compact('booking'));
    }
    // Kiểm tra phòng có trùng lịch không (API)
    protected function roomHasOverlap(int $roomId, Carbon $in, Carbon $out): bool
    {
        return BookingItem::query()
            ->where('room_id', $roomId)
            ->whereHas('booking', function ($q) use ($in, $out) {
                $q->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                    ->where(function ($qq) use ($in, $out) {
                        $qq
                            ->whereBetween('check_in_date', [$in->toDateString(), $out->copy()->subDay()->toDateString()])
                            ->orWhereBetween('check_out_date', [$in->copy()->addDay()->toDateString(), $out->toDateString()])
                            ->orWhere(function ($x) use ($in, $out) {
                                $x->where('check_in_date', '<=', $in->toDateString())
                                    ->where('check_out_date', '>=', $out->toDateString());
                            });
                    });
            })
            ->exists();
    }


    public function cart(Request $request)
    {
        $user = $request->user();

        // Tìm customer theo email (schema customers không có user_id)
        $customer = null;
        if (Schema::hasColumn('customers', 'email') && $user?->email) {
            $customer = Customer::where('email', $user->email)->first();
        }
        if (!$customer) {
            // Chưa có hồ sơ → giỏ hàng rỗng
            $bookings = collect(); // view sẽ xử lý
            return view('user.cart.index', compact('bookings'));
        }

        $bookings = Booking::with(['items.room.type', 'payments'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->paginate(10);

        return view('user.cart.index', compact('bookings'));
    }

    public function showUser(Booking $booking, Request $request)
    {
        $user = $request->user();
        $booking->load(['customer', 'items.room.type', 'payments']);

        if ($booking->customer?->email && $user?->email && $booking->customer->email !== $user->email) {
            abort(403);
        }


        return view('user.cart.show', compact('booking'));
    }
}
