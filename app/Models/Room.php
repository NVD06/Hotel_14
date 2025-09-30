<?php
// app/Models/Room.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number', 'room_type_id', 'floor', 'status', 'notes',
    ];

    public function type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }
}
