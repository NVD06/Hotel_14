<?php

namespace App\Http\Controllers;

use App\Models\{Booking, BookingItem, Customer, RoomType};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth};


class BookingController extends Controller
{
    public function index(){
        $bookings = Booking::with(['customer','items.room','payments'])->latest()->paginate(12);
        return view('bookings.index', compact('bookings'));
    }

    public function create(){
        $customers = Customer::orderBy('full_name')->get();
        $types = RoomType::orderBy('name')->get();
        return view('bookings.create', compact('customers','types'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'customer_id'     => ['required','exists:customers,id'],
        'check_in_date'   => ['required','date'],
        'check_out_date'  => ['required','date','after:check_in_date'],
        'items'           => ['required','array','min:1'],
        'items.*.room_id' => ['required','exists:rooms,id'],
        'items.*.rate'    => ['required','numeric','min:0'],
        'items.*.nights'  => ['required','integer','min:1'],
        'items.*.tax'     => ['nullable','numeric','min:0'],
        'notes'           => ['nullable','string'],
    ]);

    DB::transaction(function () use ($data) {
        $booking = Booking::create([
            'customer_id'    => $data['customer_id'],
            'check_in_date'  => $data['check_in_date'],
            'check_out_date' => $data['check_out_date'],
            'status'         => 'confirmed',
            'created_by'     => Auth::id() ?? null,   // an toàn nếu chưa login
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

        $booking->save(); // sync total_amount theo hook trong Model
    });

    return redirect()->route('bookings.index')->with('ok','Đặt phòng thành công');
}


    public function show(Booking $booking){
        $booking->load(['customer','items.room','payments','creator']);
        return view('bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking){
        $booking->delete();
        return back()->with('ok','Đã xoá booking');
    }
}
