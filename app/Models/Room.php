<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_number', 'room_type_id', 'floor', 'status', 'notes'];
     protected $casts = [
        'occupied_since' => 'datetime',
    ];
    public function type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }
    public function images()
    {
        return $this->hasMany(\App\Models\RoomImage::class)->orderBy('sort');
    }
    public function primaryImage()
    {
        return $this->hasOne(\App\Models\RoomImage::class)->where('is_primary', true);
    }
}
