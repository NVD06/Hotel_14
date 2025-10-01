<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['full_name', 'email', 'phone', 'id_number', 'address', 'date_of_birth', 'nationality', 'notes'];
    protected $casts = ['date_of_birth' => 'date'];
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
