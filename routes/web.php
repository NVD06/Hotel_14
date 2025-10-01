<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    RoomTypeController,
    RoomController,
    CustomerController,
    BookingController,
    PaymentController
};

// Route::redirect('/', '/dashboard');
Route::get('/', function () {
    return view('welcome');   // resources/views/welcome.blade.php
})->name('welcome');


// TẤT CẢ các trang bên dưới yêu cầu đăng nhập
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin only
    Route::middleware('can:admin')->group(function () {
        Route::resource('room-types', RoomTypeController::class);
        Route::resource('rooms', RoomController::class);


        // images
        Route::post('rooms/{room}/images', [\App\Http\Controllers\RoomImageController::class, 'store'])
            ->name('rooms.images.store');
        Route::put('rooms/{room}/images/{image}/primary', [\App\Http\Controllers\RoomImageController::class, 'setPrimary'])
            ->name('rooms.images.primary');
        Route::delete('rooms/{room}/images/{image}', [\App\Http\Controllers\RoomImageController::class, 'destroy'])
            ->name('rooms.images.destroy');
    });

    // Common
    Route::resource('customers', CustomerController::class);
    Route::resource('bookings',  BookingController::class);
    Route::resource('payments',  PaymentController::class)->only(['index', 'create', 'store', 'show']);

    // API
    Route::get('api/rooms/available', [RoomController::class, 'available'])->name('rooms.available');
});

// Breeze auth routes (login/register/forgot…)
require __DIR__ . '/auth.php';
