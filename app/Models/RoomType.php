<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = ['name', 'description', 'capacity', 'base_price'];
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    public function byType(RoomType $type)
    {
        // Lấy các phòng thuộc loại, kèm ảnh (ưu tiên is_primary), phân trang
        $rooms = $type->rooms()
            ->with(['images' => function ($q) {
                $q->orderByDesc('is_primary')->orderBy('id');
            }])
            ->orderBy('floor')
            ->orderBy('room_number')
            ->paginate(12);

        return view('user.rooms.bytype', compact('type', 'rooms'));
    }
}
