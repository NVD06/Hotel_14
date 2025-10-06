<?php

namespace App\Http\Controllers;

use App\Models\{Room, RoomType, BookingItem};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


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
    public function store(Request $request)
    {
        $messages = [
            'room_number.unique' => 'Phòng số này đã tồn tại ở tầng này cho loại phòng đã chọn.',
        ];

        $data = $request->validate([
            'room_type_id' => ['required', 'exists:room_types,id'],
            'room_number'  => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms', 'room_number')
                    ->where(fn($q) => $q->where('room_type_id', $request->room_type_id)
                        ->where('floor', $request->floor)),
            ],
            'floor'   => ['required', 'integer', 'min:0'],
            'status'  => ['required', Rule::in(['available', 'occupied', 'cleaning', 'maintenance'])],
            'notes'   => ['nullable', 'string', 'max:1000'],
            'images'  => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], $messages);

        $data['occupied_since'] = $data['status'] === 'occupied' ? now() : null;

        DB::beginTransaction();
        try {
            $room = Room::create($data);

            // Lưu ảnh -> storage/app/public/room/<room_number>/
            if ($request->hasFile('images')) {
                $dir = 'room/' . $data['room_number']; // === public/storage/room/<số_phòng>
                foreach ($request->file('images') as $i => $file) {
                    $filename = Str::uuid() . '.' . $file->extension();
                    $path = $file->storeAs($dir, $filename, 'public'); // trả về room/101/xxx.jpg
                    $room->images()->create([
                        'path'       => $path,
                        'is_primary' => $i === 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.room-types.index')
                ->with('ok', 'Đã tạo phòng ' . $data['room_number'] . ' (tầng ' . $data['floor'] . ').');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() === '23000') {
                return back()
                    ->withErrors(['room_number' => 'Phòng số này đã tồn tại ở tầng này cho loại phòng đã chọn.'])
                    ->withInput();
            }
            throw $e;
        }
    }
    public function edit(Room $room)
    {
        // $types = RoomType::orderBy('name')->get();
        // return view('admin.rooms.edit', compact('room', 'types'));
        $room->load('images', 'type');
        $types = RoomType::orderBy('name')->get(); // nếu muốn đổi loại phòng
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
                'string',
                'max:50',
                Rule::unique('rooms', 'room_number')
                    ->ignore($room->id)
                    ->where(fn($q) => $q->where('room_type_id', $r->input('room_type_id'))
                        ->where('floor',       $r->input('floor'))),
            ],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'floor'        => ['required', 'integer', 'min:0', 'max:200'],
            'status'       => ['required', Rule::in(['available', 'occupied', 'cleaning', 'maintenance'])],
            'notes'        => ['nullable', 'string', 'max:1000'],
        ], $messages);

        $oldStatus = $room->status;
        $newStatus = $data['status'];

        if ($newStatus === 'occupied' && $oldStatus !== 'occupied') {
            $data['occupied_since'] = now();
        }
        if ($oldStatus === 'occupied' && $newStatus !== 'occupied') {
            $data['occupied_since'] = null;
        }

        try {
            // (tùy chọn) nếu đổi số phòng -> di chuyển thư mục ảnh room/<old> -> room/<new>
            if ($room->room_number !== $data['room_number']) {
                $oldDir = 'room/' . $room->room_number;
                $newDir = 'room/' . $data['room_number'];
                if (Storage::disk('public')->exists($oldDir)) {
                    Storage::disk('public')->makeDirectory($newDir);
                    // di chuyển toàn bộ file trong thư mục
                    foreach (Storage::disk('public')->files($oldDir) as $oldPath) {
                        $basename = basename($oldPath);
                        Storage::disk('public')->move($oldPath, $newDir . '/' . $basename);
                    }
                    // cập nhật đường dẫn ảnh trong DB
                    foreach ($room->images as $img) {
                        $img->update([
                            'path' => str_replace($oldDir . '/', $newDir . '/', $img->path),
                        ]);
                    }
                    // xóa thư mục cũ nếu rỗng
                    Storage::disk('public')->deleteDirectory($oldDir);
                }
            }

            $room->update($data);

            return redirect()
                ->route('admin.room-types.index')
                ->with('ok', 'Đã cập nhật phòng.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000' || (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062)) {
                return back()
                    ->withErrors(['room_number' => 'Phòng số này đã tồn tại ở tầng này cho loại phòng đã chọn.'])
                    ->withInput();
            }
            throw $e;
        }
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
