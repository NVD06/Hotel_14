<?php

namespace App\Http\Controllers;

use App\Models\{Room, RoomType, BookingItem};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class RoomController extends Controller
{
    public function index()
    {
        $query = Room::with('type')->latest();

        if ($typeId = request('room_type_id')) {
            $query->where('room_type_id', $typeId);
        }

        $rooms = $query->paginate(12)->withQueryString();
        $types = RoomType::orderBy('id')->get();
        $activeType = $typeId ? RoomType::find($typeId) : null;

        return view('admin.rooms.index', compact('rooms', 'types', 'activeType'));
    }
    public function create()
    {
        $typeId = request('room_type_id');
        $type = $typeId ? RoomType::findOrFail($typeId) : null;

        // đi đúng flow từ room-type thì $type != null
        return view('admin.rooms.create', compact('type'));
    }
    public function store(Request $r)
    {
        $messages = [
            'room_number.unique'   => 'Phòng số này đã tồn tại ở tầng này cho loại phòng đã chọn.',
            'room_type_id.required' => 'Vui lòng chọn loại phòng.',
            'floor.required'       => 'Vui lòng nhập tầng.',
        ];

        $data = $r->validate([
            'room_number'  => [
                'required',
                'max:20',
                Rule::unique('rooms', 'room_number')->where(
                    fn($q) => $q
                        ->where('floor', $r->input('floor'))
                        ->where('room_type_id', $r->input('room_type_id'))
                ),
            ],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'floor'        => ['required', 'integer', 'min:0', 'max:200'],
            'status'       => ['required', Rule::in(['available', 'occupied', 'cleaning', 'maintenance'])],
            'notes'        => ['nullable', 'string'],
        ], $messages);

        try {
            $room = Room::create($data);
        } catch (QueryException $e) {
            // Fallback nếu DB vẫn bắn lỗi unique 
            if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) {
                return back()
                    ->withErrors(['room_number' => 'Phòng số này đã tồn tại ở tầng này cho loại phòng đã chọn.'])
                    ->withInput();
            }
            throw $e; 
        }

        return redirect()->route('admin.room-types.index')
            ->with('ok', "Đã thêm phòng {$room->room_number} ({$room->type?->name}).");
    }
    public function edit(Room $room)
    {
        $types = RoomType::orderBy('name')->get();
        return view('admin.rooms.edit', compact('room', 'types'));
    }
    public function update(Request $r, Room $room)
    {
        $messages = [
            'room_number.unique' => 'Phòng số này đã tồn tại ở tầng này cho loại phòng đã chọn.',
        ];

        $data = $r->validate([
            'room_number'  => [
                'required',
                'max:20',
                Rule::unique('rooms', 'room_number')
                    ->ignore($room->id)
                    ->where(
                        fn($q) => $q
                            ->where('floor', $r->input('floor'))
                            ->where('room_type_id', $r->input('room_type_id'))
                    ),
            ],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'floor'        => ['required', 'integer', 'min:0', 'max:200'],
            'status'       => ['required', Rule::in(['available', 'occupied', 'cleaning', 'maintenance'])],
            'notes'        => ['nullable', 'string'],
        ], $messages);

        try {
            $room->update($data);
        } catch (QueryException $e) {
            if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) {
                return back()
                    ->withErrors(['room_number' => 'Phòng số này đã tồn tại ở tầng này cho loại phòng đã chọn.'])
                    ->withInput();
            }
            throw $e;
        }

        return redirect()->route('admin.room-types.index')->with('ok', 'Đã cập nhật phòng.');
    }
    public function destroy(Room $room)
    {
        $room->delete();
        return back()->with('ok', 'Đã xoá phòng');
    }

    // API phòng trống
    public function available(Request $r)
    {
        $r->validate(['check_in' => 'required|date', 'check_out' => 'required|date|after:check_in']);
        $in = $r->date('check_in');
        $out = $r->date('check_out');
        $busy = BookingItem::whereHas('booking', function ($q) use ($in, $out) {
            $q->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                ->where('check_out_date', '>', $in)
                ->where('check_in_date', '<', $out);
        })->pluck('room_id');
        $rooms = Room::with('type')->whereNotIn('id', $busy)->where('status', '!=', 'maintenance')->orderBy('room_number')->get();
        return response()->json($rooms);
    }

    public function byType(RoomType $roomType)
    {
        $rooms = Room::with('type')
            ->where('room_type_id', $roomType->id)
            ->latest()
            ->paginate(12);

        // chế độ hiển thị đơn giản
        $mode = 'byType';
        return view('admin.rooms.index', compact('rooms', 'roomType', 'mode'));
    }
}
