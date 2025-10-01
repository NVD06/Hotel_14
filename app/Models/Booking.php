<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = ['booking_code', 'customer_id', 'check_in_date', 'check_out_date', 'status', 'total_amount', 'created_by', 'notes'];
    protected $casts = ['check_in_date' => 'date', 'check_out_date' => 'date', 'total_amount' => 'decimal:2'];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->booking_code)) $m->booking_code = 'BK-' . Str::upper(Str::random(8));
        });
        static::saving(function (self $m) {
            $m->total_amount = $m->items()->sum('subtotal');
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
