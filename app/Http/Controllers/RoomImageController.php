<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RoomImageController extends Controller
{
    // POST admin/rooms/{room}/images  (rooms.images.store)
    public function store(Request $request, Room $room)
    {
        $request->validate([
            'images'   => ['required','array'],
            'images.*' => ['image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        // Thư mục đúng quy ước: room/<số_phòng> (đã dùng ở create)
        $dir = 'room/'.$room->room_number;

        foreach ($request->file('images') as $file) {
            $filename = Str::uuid().'.'.$file->extension();
            // lưu vào storage/app/public/room/<số_phòng>/<uuid>.jpg
            $path = $file->storeAs($dir, $filename, 'public');

            $room->images()->create([
                'path'       => $path,   // ví dụ: room/101/xxxx.jpg
                'is_primary' => false,
            ]);
        }

        return back()->with('ok', 'Đã thêm ảnh.');
    }

    // PUT admin/rooms/{room}/images/{image}/primary  (rooms.images.primary)
    public function setPrimary(Room $room, RoomImage $image)
    {
        abort_unless($image->room_id === $room->id, 404);

        $room->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('ok', 'Đã đặt ảnh chính.');
    }

    // DELETE admin/rooms/{room}/images/{image}  (rooms.images.destroy)
    public function destroy(Room $room, RoomImage $image)
    {
        abort_unless($image->room_id === $room->id, 404);

        // Xóa file thật trên disk public
        Storage::disk('public')->delete($image->path);

        $image->delete();

        return back()->with('ok', 'Đã xoá ảnh.');
    }
}
