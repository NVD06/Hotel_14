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

/*
|--------------------------------------------------------------------------
| Trang gốc (/) – điều hướng thông minh
| - Nếu đã đăng nhập: admin -> /admin ; user -> /dashboard
| - Nếu chưa đăng nhập: hiện trang welcome
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return Gate::allows('admin')
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');


// Admin routes (UI riêng)


Route::middleware(['auth','can:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        // Trang giao diện admin (thuần Blade)
        Route::view('/', 'admin.dashboard')->name('dashboard');
        Route::view('/revenue', 'admin.revenue')->name('revenue');
        Route::view('/pages', 'admin.pages.index')->name('pages.index');

        // Quản lý phòng & loại phòng (admin)
        Route::resource('room-types', RoomTypeController::class);
        Route::resource('rooms', RoomController::class);

        // Ảnh phòng (admin)
        Route::post('rooms/{room}/images', [RoomImageController::class, 'store'])
            ->name('rooms.images.store');
        Route::put('rooms/{room}/images/{image}/primary', [RoomImageController::class, 'setPrimary'])
            ->name('rooms.images.primary');
        Route::delete('rooms/{room}/images/{image}', [RoomImageController::class, 'destroy'])
            ->name('rooms.images.destroy');
    });


// User routes (ứng dụng cho khách/user đã đăng nhập)

Route::middleware(['auth','verified'])->group(function () {

    // Dashboard user: nếu là admin -> đẩy về admin
    Route::get('/dashboard', function () {
        return Gate::allows('admin')
            ? redirect()->route('admin.dashboard')
            : view('dashboard');
    })->name('dashboard');

    // Hồ sơ
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Nghiệp vụ phía user
    Route::resource('customers', CustomerController::class);
    Route::resource('bookings',  BookingController::class);
    Route::resource('payments',  PaymentController::class)->only(['index','create','store','show']);

    // API dùng cho user
    Route::get('api/rooms/available', [RoomController::class, 'available'])->name('rooms.available');
});


require __DIR__.'/auth.php';
