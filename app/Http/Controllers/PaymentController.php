<?php

namespace App\Http\Controllers;

use App\Models\{Payment, Booking};
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(){
        $payments = Payment::with('booking.customer')->latest()->paginate(20);
        return view('payments.index', compact('payments'));
    }

    public function create(){
        $bookings = Booking::with('customer')->orderByDesc('id')->limit(50)->get();
        return view('payments.create', compact('bookings'));
    }

    public function store(Request $r){
        $data = $r->validate([
            'booking_id'=>['required','exists:bookings,id'],
            'amount'=>['required','numeric','min:0.01'],
            'method'=>['required','in:cash,card,bank_transfer,ewallet'],
            'paid_at'=>['required','date'],
            'reference'=>['nullable','string','max:100'],
            'status'=>['required','in:paid,refunded,void'],
            'notes'=>['nullable','string'],
        ]);
        Payment::create($data);

        // optional: cập nhật trạng thái nếu trả đủ
        $booking = Booking::withSum('payments as paid_sum','amount')->find($data['booking_id']);
        if ($booking && $booking->paid_sum >= $booking->total_amount && $booking->status !== 'checked_out') {
            $booking->update(['status'=>'checked_out']);
        }

        return redirect()->route('payments.index')->with('ok','Đã ghi nhận thanh toán');
    }

    public function show(Payment $payment){
        $payment->load('booking.customer');
        return view('payments.show', compact('payment'));
    }
}
