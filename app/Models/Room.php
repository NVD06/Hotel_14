<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_number', 'room_type_id', 'status'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }
}