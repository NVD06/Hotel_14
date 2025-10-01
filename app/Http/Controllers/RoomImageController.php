<?php

namespace App\Http\Controllers;

use App\Models\{Room, RoomImage};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomImageController extends Controller
{
    // POST /rooms/{room}/images
    public function store(Request $request, Room $room)
    {
        $data = $request->validate([
            'images.*'  => ['required','file','mimes:jpeg,jpg,png,webp','max:5120'], // 5MB/ảnh
            'captions'  => ['array'],
            'captions.*'=> ['nullable','string','max:120'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                // Tên file an toàn
                $name = uniqid('room_').'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs('rooms/'.$room->id, $name, 'public'); // storage/app/public/rooms/{id}/...

                RoomImage::create([
                    'room_id'    => $room->id,
                    'path'       => $path,
                    'caption'    => $request->input("captions.$idx"),
                    'is_primary' => !$room->images()->exists() && $idx === 0, // ảnh đầu tiên -> primary
                    'sort'       => $room->images()->max('sort') + 1,
                ]);
            }
        }

        return back()->with('ok','Đã tải ảnh lên');
    }

    // PUT /rooms/{room}/images/{image}/primary
    public function setPrimary(Room $room, RoomImage $image)
    {
        abort_unless($image->room_id === $room->id, 404);

        $room->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('ok','Đã đặt ảnh đại diện');
    }

    // DELETE /rooms/{room}/images/{image}
    public function destroy(Room $room, RoomImage $image)
    {
        abort_unless($image->room_id === $room->id, 404);

        // Xoá file & bản ghi
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('ok','Đã xoá ảnh');
    }
}
