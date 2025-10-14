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
use App\Http\Controllers\PublicRoomController;
/* -------- Public landing -------- */

Route::view('/', 'welcome')->name('welcome');

/* -------- Public pages -------- */
Route::view('/about',   'User.about')->name('about');
Route::view('/rooms',   'User.room')->name('rooms');
Route::view('/contact', 'User.contact')->name('contact');


/* -------- Admin area (/admin) -------- */
Route::middleware(['auth', 'can:admin'])
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
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Danh sách phòng theo loại
        Route::get('room-types/{roomType}/rooms', [RoomController::class, 'byType'])
            ->name('room-types.rooms');
        Route::resource('rooms', RoomController::class);
        // ẢNH PHÒNG
        Route::post('rooms/{room}/images',                [RoomImageController::class, 'store'])->name('rooms.images.store');
        Route::put('rooms/{room}/images/{image}/primary', [RoomImageController::class, 'setPrimary'])->name('rooms.images.primary');
        Route::delete('rooms/{room}/images/{image}',      [RoomImageController::class, 'destroy'])->name('rooms.images.destroy');
    });

/* -------- Khu user sau đăng nhập -------- */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/home', 'User.home')->name('user.home');

    Route::get('/dashboard', function () {
        return Gate::allows('admin')
            ? redirect()->route('admin.home')
            : redirect()->route('user.home');
    })->name('dashboard');

    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('customers', CustomerController::class);
    Route::resource('bookings',  BookingController::class);
    Route::resource('payments',  PaymentController::class)->only(['index', 'create', 'store', 'show']);

    // Public phòng

    Route::get('/rooms', [PublicRoomController::class, 'index'])
        ->name('user.rooms.index');

    // Trang xem PHÒNG theo LOẠI
    Route::get('/rooms/type/{type}', [PublicRoomController::class, 'byType'])
        ->name('user.rooms.byType');

    //  Booking (User)

    // Trang form đặt phòng
    Route::get('/rooms/{room}/book', [BookingController::class, 'book'])->name('user.rooms.book');

    // Bước 1: xác nhận -> chuyển sang thanh toán (chỉ validate + lưu session)
    Route::post('/rooms/{room}/book/review', [BookingController::class, 'review'])->name('user.rooms.book.review');

    // Bước 2: thanh toán từ dữ liệu trong session (chưa có booking trong DB)
    Route::get('/checkout', [PaymentController::class, 'checkoutSession'])->name('user.checkout');
    Route::post('/checkout', [PaymentController::class, 'payAndCreate'])->name('user.checkout.pay');

    // (giữ nguyên) trang xác nhận sau khi đã tạo booking
    Route::get('/rooms/bookings/{booking}/confirm', [BookingController::class, 'confirm'])
        ->name('user.rooms.bookings.confirm');


    // Giỏ hàng (đơn của tôi)
    Route::get('/cart', [BookingController::class, 'cart'])->name('user.cart');

    // Xem 1 đơn trong giỏ (nếu muốn trang riêng)
    Route::get('/cart/bookings/{booking}', [BookingController::class, 'showUser'])
        ->name('user.cart.show');

    // API kiểm tra phòng trống
    Route::get('api/rooms/available', [RoomController::class, 'available'])->name('rooms.available');
});

require __DIR__ . '/auth.php';
