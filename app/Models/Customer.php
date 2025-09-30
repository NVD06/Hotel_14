<?php
// app/Models/Customer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'email', 'phone', 'id_number', 'address',
        'date_of_birth', 'nationality', 'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
