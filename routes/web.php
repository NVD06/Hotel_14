<?php

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

// Controllers (đều ở App\Http\Controllers)
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomImageController;
use App\Http\Controllers\RoomTypeController;

/* -------- Public landing -------- */
Route::view('/', 'welcome')->name('welcome');

/* -------- Public pages -------- */
Route::view('/about',   'User.about')->name('about');
Route::view('/rooms',   'User.room')->name('rooms');
Route::view('/contact', 'User.contact')->name('contact');

/* -------- Admin area (/admin) -------- */
Route::middleware(['auth','can:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

        // Trang tĩnh trong admin
        Route::view('/',        'admin.home')->name('home');
        Route::view('/revenue', 'admin.revenue')->name('revenue');
        Route::view('/pages',   'admin.pages')->name('pages.index');

        // LOẠI PHÒNG
        Route::resource('room-types', RoomTypeController::class);

        // PHÒNG
        Route::resource('rooms', RoomController::class)
            ->only(['index','create','store','edit','update','destroy']);

        // Danh sách phòng theo loại
        Route::get('room-types/{roomType}/rooms', [RoomController::class,'byType'])
            ->name('room-types.rooms');

        // ẢNH PHÒNG
        Route::post('rooms/{room}/images',                [RoomImageController::class,'store'])->name('rooms.images.store');
        Route::put('rooms/{room}/images/{image}/primary', [RoomImageController::class,'setPrimary'])->name('rooms.images.primary');
        Route::delete('rooms/{room}/images/{image}',      [RoomImageController::class,'destroy'])->name('rooms.images.destroy');
    });

/* -------- Khu user sau đăng nhập -------- */
Route::middleware(['auth','verified'])->group(function () {

    Route::view('/home', 'User.home')->name('user.home');

    Route::get('/dashboard', function () {
        return Gate::allows('admin')
            ? redirect()->route('admin.home')
            : redirect()->route('user.home');
    })->name('dashboard');

    Route::get('/profile',   [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class,'destroy'])->name('profile.destroy');

    Route::resource('customers', CustomerController::class);
    Route::resource('bookings',  BookingController::class);
    Route::resource('payments',  PaymentController::class)->only(['index','create','store','show']);

    // API kiểm tra phòng trống (nếu bạn dùng)
    Route::get('api/rooms/available', [RoomController::class,'available'])->name('rooms.available');
});

require __DIR__.'/auth.php';
