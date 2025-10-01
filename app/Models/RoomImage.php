<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    protected $fillable = ['room_id','path','caption','is_primary','sort'];

    public function room() { return $this->belongsTo(Room::class); }

    // URL public để hiển thị
    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }
}
