<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $defaults = [
            ['name' => 'Bình thường',  'capacity' => 2, 'base_price' => 300000, 'description' => 'Phòng tiêu chuẩn'],
            ['name' => 'Trung',        'capacity' => 3, 'base_price' => 500000, 'description' => 'Phòng trung cấp'],
            ['name' => 'Thượng hạng',  'capacity' => 4, 'base_price' => 900000, 'description' => 'Phòng cao cấp'],
        ];
        foreach ($defaults as $d) {
            RoomType::firstOrCreate(['name' => $d['name']], $d);
        }

    
        $types = RoomType::whereIn('name', ['Bình thường', 'Trung', 'Thượng hạng'])
            ->orderByRaw("FIELD(name,'Bình thường','Trung','Thượng hạng')")
            ->get();

        return view('admin.room-types.index', compact('types'));
    }
    public function create()
    {
        return view('admin.room-types.create');
    }
    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => ['required', 'max:100', 'unique:room_types,name'],
            'description' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
        ]);
        RoomType::create($data);
        return redirect()->route('admin.room-types.index')->with('ok', 'Đã tạo loại phòng');
    }
    public function edit(RoomType $roomType)
    {
        return view('room_types.edit', compact('roomType'));
    }
    public function update(Request $r, RoomType $roomType)
    {
        $data = $r->validate([
            'name' => ['required', 'max:100', 'unique:room_types,name,' . $roomType->id],
            'description' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
        ]);
        $roomType->update($data);
        return redirect()->route('admin.room-types.index')->with('ok', 'Đã cập nhật');
    }
    public function destroy(RoomType $roomType)
    {
        $roomType->delete();
        return back()->with('ok', 'Đã xoá');
    }
}
