<?php

namespace App\Http\Controllers;

use App\Models\{Room, RoomType, BookingItem};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index(){ $rooms = Room::with('type')->latest()->paginate(12); return view('rooms.index', compact('rooms')); }
    public function create(){ $types = RoomType::orderBy('name')->get(); return view('rooms.create', compact('types')); }
    public function store(Request $r){
        $data = $r->validate([
            'room_number'=>['required','max:20','unique:rooms,room_number'],
            'room_type_id'=>['required','exists:room_types,id'],
            'floor'=>['nullable','integer','min:0','max:200'],
            'status'=>['required', Rule::in(['available','occupied','cleaning','maintenance'])],
            'notes'=>['nullable','string'],
        ]);
        Room::create($data);
        return redirect()->route('rooms.index')->with('ok','Đã tạo phòng');
    }
    public function edit(Room $room){ $types = RoomType::orderBy('name')->get(); return view('rooms.edit', compact('room','types')); }
    public function update(Request $r, Room $room){
        $data = $r->validate([
            'room_number'=>['required','max:20','unique:rooms,room_number,'.$room->id],
            'room_type_id'=>['required','exists:room_types,id'],
            'floor'=>['nullable','integer','min:0','max:200'],
            'status'=>['required', Rule::in(['available','occupied','cleaning','maintenance'])],
            'notes'=>['nullable','string'],
        ]);
        $room->update($data);
        return redirect()->route('rooms.index')->with('ok','Đã cập nhật');
    }
    public function destroy(Room $room){ $room->delete(); return back()->with('ok','Đã xoá'); }

    // API phòng trống
    public function available(Request $r){
        $r->validate(['check_in'=>'required|date','check_out'=>'required|date|after:check_in']);
        $in = $r->date('check_in'); $out = $r->date('check_out');
        $busy = BookingItem::whereHas('booking', function($q) use($in,$out){
            $q->whereIn('status',['pending','confirmed','checked_in'])
              ->where('check_out_date','>', $in)
              ->where('check_in_date','<', $out);
        })->pluck('room_id');
        $rooms = Room::with('type')->whereNotIn('id',$busy)->where('status','!=','maintenance')->orderBy('room_number')->get();
        return response()->json($rooms);
    }
}
