<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Booking;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // giữ nguyên casts & fillable mặc định của Laravel/Breeze
    protected $fillable = ['name','email','password'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function bookingsCreated()
    {
        return $this->hasMany(Booking::class, 'created_by');
    }
}
