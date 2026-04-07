<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDataController;
use App\Http\Controllers\Admin\AmenityAdminController;
use App\Http\Controllers\Admin\DestinationAdminController;
use App\Http\Controllers\Admin\HomestayAdminController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\DestinationController as ClientDestinationController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\HomestayDetailController;
use App\Http\Controllers\Client\NotificationController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\RoomSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/diem-den/{category?}', [ClientDestinationController::class, 'show'])->name('destinations.show');
Route::get('/tim-phong', [RoomSearchController::class, 'index'])->name('rooms.search');
Route::get('/phong/{id}', [HomestayDetailController::class, 'show'])->name('homestay.show');
Route::redirect('/phong/{id}/dat-phong', '/phong/{id}#dat-phong', 301)->name('homestay.booking');
Route::post('/phong/{id}/dat-phong/demo', [PaymentController::class, 'storeDemoBooking'])->name('homestay.booking.demo');
Route::get('/thanh-toan', [PaymentController::class, 'show'])->name('payment.show');
Route::view('/thong-tin-ca-nhan', 'clients.profile.show')->name('profile.page');
Route::view('/cau-hoi-thuong-gap', 'clients.pages.faq')->name('pages.faq');
Route::view('/blog', 'clients.blog.index')->name('blog.index');
Route::view('/ve-chung-toi', 'clients.pages.about')->name('pages.about');
Route::view('/lien-he', 'clients.pages.contact')->name('pages.contact');
Route::view('/chinh-sach-dat-phong', 'clients.pages.policy_booking')->name('pages.policy_booking');
Route::view('/chinh-sach-bao-mat', 'clients.pages.privacy_policy')->name('pages.privacy_policy');
Route::view('/dieu-khoan-dich-vu', 'clients.pages.terms')->name('pages.terms');

// Authenticated client routes
Route::middleware('auth')->group(function () {
    Route::view('/yeu-thich', 'clients.wishlist.index')->name('wishlist.index');
    Route::view('/dat-phong/lich-su', 'clients.bookings.history')->name('bookings.history');
    Route::post('/thong-bao/{id}/doc', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/thong-bao/doc-tat', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});

// Auth routes
Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login.page');
Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register.page');
Route::post('/dang-nhap', [AuthController::class, 'login'])->name('auth.login');
Route::post('/dang-ky', [AuthController::class, 'register'])->name('auth.register');
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('auth.logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'loginAccount'])->name('login.account');
    Route::post('/login-key', [AdminAuthController::class, 'loginKey'])->name('login.key');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [AdminDataController::class, 'dashboard'])->name('dashboard');

    Route::get('/categories', [AdminDataController::class, 'categories'])->name('categories');
    
    Route::get('/destinations', [DestinationAdminController::class, 'index'])->name('destinations');
    Route::get('/destinations/create', [DestinationAdminController::class, 'create'])->name('destinations.create');
    Route::post('/destinations', [DestinationAdminController::class, 'store'])->name('destinations.store');
    Route::get('/destinations/{destination}/edit', [DestinationAdminController::class, 'edit'])->name('destinations.edit');
    Route::put('/destinations/{destination}', [DestinationAdminController::class, 'update'])->name('destinations.update');
    Route::delete('/destinations/{destination}', [DestinationAdminController::class, 'destroy'])->name('destinations.destroy');

    Route::get('/users', [AdminDataController::class, 'users'])->name('users');

    Route::get('/homestays', [HomestayAdminController::class, 'index'])->name('homestays');
    Route::get('/homestays/create', [HomestayAdminController::class, 'create'])->name('homestays.create');
    Route::post('/homestays', [HomestayAdminController::class, 'store'])->name('homestays.store');
    Route::post('/homestays/upload-editor-image', [HomestayAdminController::class, 'uploadEditorImage'])->name('homestays.upload-editor-image');
    Route::get('/homestays/{homestay}/edit', [HomestayAdminController::class, 'edit'])->name('homestays.edit');
    Route::get('/homestays/{homestay}/calendar', [HomestayAdminController::class, 'calendar'])->name('homestays.calendar');
    Route::put('/homestays/{homestay}', [HomestayAdminController::class, 'update'])->name('homestays.update');
    Route::delete('/homestays/{homestay}', [HomestayAdminController::class, 'destroy'])->name('homestays.destroy');

    Route::get('/bookings', [AdminDataController::class, 'bookings'])->name('bookings');
    
    Route::get('/amenities', [AmenityAdminController::class, 'index'])->name('amenities');
    Route::get('/amenities/create', [AmenityAdminController::class, 'create'])->name('amenities.create');
    Route::post('/amenities', [AmenityAdminController::class, 'store'])->name('amenities.store');
    Route::get('/amenities/{amenity}/edit', [AmenityAdminController::class, 'edit'])->name('amenities.edit');
    Route::put('/amenities/{amenity}', [AmenityAdminController::class, 'update'])->name('amenities.update');
    Route::delete('/amenities/{amenity}', [AmenityAdminController::class, 'destroy'])->name('amenities.destroy');
    
    Route::get('/promotions', [AdminDataController::class, 'promotions'])->name('promotions');
    Route::get('/promotions/create', [AdminDataController::class, 'promotionsCreate'])->name('promotions.create');
    Route::post('/promotions', [AdminDataController::class, 'promotionsStore'])->name('promotions.store');
    Route::get('/promotions/{promotion}/edit', [AdminDataController::class, 'promotionsEdit'])->name('promotions.edit');
    Route::put('/promotions/{promotion}', [AdminDataController::class, 'promotionsUpdate'])->name('promotions.update');
    Route::delete('/promotions/{promotion}', [AdminDataController::class, 'promotionsDestroy'])->name('promotions.destroy');
    
    Route::get('/reports', [AdminDataController::class, 'reports'])->name('reports');

    Route::get('/blogs', [AdminDataController::class, 'blogs'])->name('blogs');
    Route::get('/reviews', [AdminDataController::class, 'reviews'])->name('reviews');
    Route::get('/tickets', [AdminDataController::class, 'tickets'])->name('tickets');
});

