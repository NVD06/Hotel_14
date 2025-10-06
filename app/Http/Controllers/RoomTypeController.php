<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        
        $types = RoomType::orderBy('name')->get(); 

        return view('admin.room-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.room-types.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'        => ['required', 'max:100', 'unique:room_types,name'],
            'description' => ['nullable', 'string'],
            'capacity'    => ['required', 'integer', 'min:1'],
            'base_price'  => ['required', 'numeric', 'min:0'],
        ]);

        RoomType::create($data);
        return redirect()->route('admin.room-types.index')->with('ok', 'Đã tạo loại phòng');
    }

    public function edit(RoomType $roomType)
    {
        return view('admin.room-types.edit', compact('roomType'));
    }

    public function update(Request $r, RoomType $roomType)
    {
        $data = $r->validate([
            'name'        => ['required', 'max:100', 'unique:room_types,name,' . $roomType->id],
            'description' => ['nullable', 'string'],
            'capacity'    => ['required', 'integer', 'min:1'],
            'base_price'  => ['required', 'numeric', 'min:0'],
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
