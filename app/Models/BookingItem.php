<?php
// app/Models/BookingItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'room_id', 'rate', 'nights', 'tax', 'subtotal',
    ];

    protected $casts = [
        'rate'     => 'decimal:2',
        'tax'      => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
