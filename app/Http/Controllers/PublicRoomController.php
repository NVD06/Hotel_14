<?php

namespace App\Http\Controllers;
use App\Models\{RoomType, Room};
use Carbon\Carbon;


class PublicRoomController extends Controller
{
    // Trang index: danh sách LOẠI PHÒNG
    public function index()
    {
        $q = request('q');
        $capacity = request('capacity');

        $types = RoomType::query()
            ->when($q, fn($qq) =>
                $qq->where(function($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                      ->orWhere('description','like',"%{$q}%");
                })
            )
            ->when($capacity, fn($qq) => $qq->where('capacity', $capacity))
            ->withCount('rooms')
            ->with(['rooms' => function ($r) {
                $r->with(['images' => function ($i) {
                    $i->orderByDesc('is_primary')->orderBy('id')->limit(1);
                }])->limit(1);
            }])
            ->orderBy('name')
            ->paginate(9);

        return view('user.rooms.index', compact('types'));
    }

    // Trang theo LOẠI: liệt kê các PHÒNG thuộc 1 loại
    public function byType(RoomType $type)
    {
        $rooms = $type->rooms()
            ->with(['images' => function ($i) {
                $i->orderByDesc('is_primary')->orderBy('id');
            }])
            ->orderBy('room_number')   // đổi theo nhu cầu
            ->paginate(12);

        return view('user.rooms.bytype', compact('type','rooms'));
    }
    
}
