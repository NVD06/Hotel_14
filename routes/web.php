<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    RoomTypeController,
    RoomController,
    CustomerController,
    BookingController,
    PaymentController,
    RoomImageController
};
Route::view('/', 'welcome')->name('welcome'); // landing cho khách


/* -------- Public landing (sau logout quay về đây) -------- */
Route::view('/', 'welcome')->name('welcome');

/* -------- Public pages (nếu bạn muốn giữ) -------- */
Route::view('/about',   'User.about')->name('about');
Route::view('/rooms',   'User.room')->name('rooms');
Route::view('/contact', 'User.contact')->name('contact');

/* -------- Admin area (/admin) -------- */
Route::middleware(['auth','can:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::view('/',        'admin.home')->name('home');
        Route::view('/revenue', 'admin.revenue')->name('revenue');
        Route::view('/pages',   'admin.pages')->name('pages.index');

        Route::resource('room-types', RoomTypeController::class);
        Route::resource('rooms',      RoomController::class);

        Route::post  ('rooms/{room}/images',                 [RoomImageController::class, 'store'])->name('rooms.images.store');
        Route::put   ('rooms/{room}/images/{image}/primary', [RoomImageController::class, 'setPrimary'])->name('rooms.images.primary');
        Route::delete('rooms/{room}/images/{image}',         [RoomImageController::class, 'destroy'])->name('rooms.images.destroy');
    });

/* -------- Khu user sau đăng nhập -------- */
Route::middleware(['auth','verified'])->group(function () {
    // Dashboard của user: /home
    Route::view('/home', 'user.home')->name('user.home');

    // Giữ /dashboard làm điểm rơi mặc định, nhưng điều hướng theo vai trò
    Route::get('/dashboard', function () {
        return Gate::allows('admin')
            ? redirect()->route('admin.home')
            : redirect()->route('user.home');
    })->name('dashboard');

    // Profile + nghiệp vụ user
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('customers', CustomerController::class);
    Route::resource('bookings',  BookingController::class);
    Route::resource('payments',  PaymentController::class)->only(['index','create','store','show']);

    Route::get('api/rooms/available', [RoomController::class, 'available'])->name('rooms.available');
});

require __DIR__.'/auth.php';
