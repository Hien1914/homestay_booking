<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDataController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAmenityController;
use App\Http\Controllers\Admin\AdminDestinationController;
use App\Http\Controllers\Admin\AdminHomestayController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Client\ApplyHostController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\DestinationController as ClientDestinationController;
use App\Http\Controllers\Client\FavoriteController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\HomestayController as ClientHomestayController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\RoomSearchController;
use App\Http\Controllers\Client\GoogleAuthController;
use App\Http\Controllers\Host\DashboardController as HostDashboardController;
use App\Http\Controllers\Host\HomestayController as HostHomestayController;
use App\Http\Controllers\Host\BookingController as HostBookingController;
use App\Http\Controllers\Host\EarningsController as HostEarningsController;
use App\Http\Controllers\Host\PromotionController as HostPromotionController;
use App\Http\Controllers\Host\ReviewController as HostReviewController;
use App\Http\Controllers\Host\AmenityController as HostAmenityController;
use App\Http\Controllers\Host\DestinationController as HostDestinationController;
use App\Http\Controllers\Host\CustomerController as HostCustomerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/diem-den/{category?}', [ClientDestinationController::class, 'show'])->name('destinations.show');
Route::get('/tim-phong', [RoomSearchController::class, 'index'])->name('pages.search');
Route::get('/phong/{slug}', [ClientHomestayController::class, 'show'])->name('homestay.show');
Route::redirect('/phong/{slug}/dat-phong', '/phong/{slug}#dat-phong', 301)->name('homestay.booking');
Route::post('/phong/{slug}/dat-phong/demo', [PaymentController::class, 'storeDemoBooking'])->name('homestay.booking.demo');
Route::post('/phong/{slug}/dat-phong', [PaymentController::class, 'storeBooking'])->middleware('auth')->name('homestay.booking.store');
Route::get('/thanh-toan/{booking}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/thanh-toan/xac-nhan', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
Route::view('/cau-hoi-thuong-gap', 'clients.pages.faq')->name('pages.faq');
Route::get('/blog', [\App\Http\Controllers\Client\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\Client\BlogController::class, 'show'])->name('blog.show');
Route::view('/ve-chung-toi', 'clients.pages.about')->name('pages.about');
Route::view('/chinh-sach-dat-phong', 'clients.pages.booking-policy')->name('pages.policy_booking');
Route::view('/chinh-sach-bao-mat', 'clients.pages.privacy-policy')->name('pages.privacy_policy');
Route::view('/dieu-khoan-dich-vu', 'clients.pages.terms')->name('pages.terms');

/*
|--------------------------------------------------------------------------
| Authenticated Client Routes (user + host)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::view('/thong-tin-ca-nhan', 'clients.profile')->name('profile.page');
    Route::put('/thong-tin-ca-nhan', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/yeu-thich', [FavoriteController::class, 'index'])->name('favorite.index');
    Route::post('/yeu-thich/{homestay}/toggle', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
    Route::delete('/yeu-thich/{homestay}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
    Route::post('/phong/{slug}/danh-gia/{booking}', [\App\Http\Controllers\Client\ReviewController::class, 'store'])->name('homestay.review.store');
    Route::get('/dat-phong/lich-su', [\App\Http\Controllers\Client\BookingHistoryController::class, 'history'])->name('bookings.history');
    Route::post('/dat-phong/lich-su/{id}/huy', [\App\Http\Controllers\Client\BookingHistoryController::class, 'cancel'])->name('bookings.cancel');
    Route::put('/dat-phong/lich-su/{booking}/checkin', [\App\Http\Controllers\Client\BookingHistoryController::class, 'checkin'])->name('bookings.checkin');
    Route::put('/dat-phong/lich-su/{booking}/checkout', [\App\Http\Controllers\Client\BookingHistoryController::class, 'checkout'])->name('bookings.checkout');

    // Đăng ký làm host
    Route::get('/dang-ky-host', [ApplyHostController::class, 'create'])->name('apply-host.create');
    Route::post('/dang-ky-host', [ApplyHostController::class, 'store'])->name('apply-host.store');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (login, register, logout)
|--------------------------------------------------------------------------
*/

Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login.page');
Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register.page');
Route::post('/dang-nhap', [AuthController::class, 'login'])->name('auth.login');
Route::post('/dang-ky', [AuthController::class, 'register'])->name('auth.register');
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

/*
|--------------------------------------------------------------------------
| Host Routes (role: host)
|--------------------------------------------------------------------------
*/

Route::prefix('host')->name('host.')->middleware('role:host')->group(function () {

    // Dashboard
    Route::get('/dashboard', [HostDashboardController::class, 'index'])->name('dashboard');
    Route::redirect('/', '/host/dashboard');

    // Quản lý homestay
    Route::get('/homestays', [HostHomestayController::class, 'index'])->name('homestays.index');
    Route::get('/homestays/create', [HostHomestayController::class, 'create'])->name('homestays.create');
    Route::post('/homestays', [HostHomestayController::class, 'store'])->name('homestays.store');
    Route::get('/homestays/{homestay}/edit', [HostHomestayController::class, 'edit'])->name('homestays.edit');
    Route::put('/homestays/{homestay}', [HostHomestayController::class, 'update'])->name('homestays.update');
    Route::delete('/homestays/{homestay}', [HostHomestayController::class, 'destroy'])->name('homestays.destroy');

    // Quản lý booking
    Route::get('/bookings', [HostBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [HostBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/cancel-approve', [HostBookingController::class, 'cancelApprove'])->name('bookings.cancel-approve');

    // Thu nhập & rút tiền
    Route::get('/earnings', [HostEarningsController::class, 'index'])->name('earnings.index');
    Route::post('/payouts', [\App\Http\Controllers\Host\PayoutController::class, 'store'])->name('payouts.store');

    // Khuyến mãi
    Route::get('/promotions', [HostPromotionController::class, 'index'])->name('promotions.index');
    Route::get('/promotions/create', [HostPromotionController::class, 'create'])->name('promotions.create');
    Route::post('/promotions', [HostPromotionController::class, 'store'])->name('promotions.store');
    Route::get('/promotions/{promotion}/edit', [HostPromotionController::class, 'edit'])->name('promotions.edit');
    Route::put('/promotions/{promotion}', [HostPromotionController::class, 'update'])->name('promotions.update');
    Route::delete('/promotions/{promotion}', [HostPromotionController::class, 'destroy'])->name('promotions.destroy');

    // Tiện nghi
    Route::get('/amenities', [HostAmenityController::class, 'index'])->name('amenities');
    Route::get('/amenities/create', [HostAmenityController::class, 'create'])->name('amenities.create');
    Route::post('/amenities', [HostAmenityController::class, 'store'])->name('amenities.store');
    Route::get('/amenities/{amenity}/edit', [HostAmenityController::class, 'edit'])->name('amenities.edit');
    Route::put('/amenities/{amenity}', [HostAmenityController::class, 'update'])->name('amenities.update');
    Route::delete('/amenities/{amenity}', [HostAmenityController::class, 'destroy'])->name('amenities.destroy');

    // Điểm đến (Bỏ CRUD, host gửi qua form homestay)
    /*
    Route::get('/destinations', [HostDestinationController::class, 'index'])->name('destinations');
    Route::get('/destinations/create', [HostDestinationController::class, 'create'])->name('destinations.create');
    Route::post('/destinations', [HostDestinationController::class, 'store'])->name('destinations.store');
    Route::get('/destinations/{destination}/edit', [HostDestinationController::class, 'edit'])->name('destinations.edit');
    Route::put('/destinations/{destination}', [HostDestinationController::class, 'update'])->name('destinations.update');
    Route::delete('/destinations/{destination}', [HostDestinationController::class, 'destroy'])->name('destinations.destroy');
    */

    // Đánh giá
    Route::get('/reviews', [HostReviewController::class, 'index'])->name('reviews');

    Route::get('/customers', [\App\Http\Controllers\Host\CustomerController::class, 'index'])->name('customers.index');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login-key', [AdminAuthController::class, 'loginKey'])->name('login.key');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [AdminDataController::class, 'dashboard'])->name('dashboard');

    // Destinations
    Route::get('/destinations', [AdminDestinationController::class, 'index'])->name('destinations');
    Route::get('/destinations/create', [AdminDestinationController::class, 'create'])->name('destinations.create');
    Route::post('/destinations', [AdminDestinationController::class, 'store'])->name('destinations.store');
    Route::get('/destinations/{destination}/edit', [AdminDestinationController::class, 'edit'])->name('destinations.edit');
    Route::put('/destinations/{destination}', [AdminDestinationController::class, 'update'])->name('destinations.update');
    Route::put('/destinations/{destination}/approve', [AdminDestinationController::class, 'approve'])->name('destinations.approve');
    Route::delete('/destinations/{destination}', [AdminDestinationController::class, 'destroy'])->name('destinations.destroy');

    // Users
    Route::get('/users', [AdminDataController::class, 'users'])->name('users');

    // Host Applications (duyệt đăng ký host)
    Route::get('/host-applications', [AdminUserController::class, 'hostApplications'])->name('host-applications');
    Route::put('/host-applications/{application}/approve', [AdminUserController::class, 'approveHost'])->name('host-applications.approve');
    Route::put('/host-applications/{application}/reject', [AdminUserController::class, 'rejectHost'])->name('host-applications.reject');

    // Homestays
    Route::get('/homestays', [AdminHomestayController::class, 'index'])->name('homestays');
    Route::put('/homestays/{homestay}/approve', [AdminHomestayController::class, 'approve'])->name('homestays.approve');
    Route::delete('/homestays/{homestay}', [AdminHomestayController::class, 'destroy'])->name('homestays.destroy');
    Route::delete('/homestays/reviews/{review}', [AdminHomestayController::class, 'destroyReview'])->name('homestays.reviews.destroy');

    // Bookings
    Route::get('/bookings', [AdminDataController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}/detail', [AdminDataController::class, 'bookingDetail'])->name('bookings.detail');
    Route::put('/bookings/{booking}/confirm-payment', [AdminDataController::class, 'confirmPayment'])->name('payments.confirm');

    
    // Payments
    Route::get('/payments', [AdminDataController::class, 'payments'])->name('payments');
    Route::get('/payments/{payment}', [AdminDataController::class, 'paymentShow'])->name('payments.show');

    
    // Amenities
    Route::get('/amenities', [AdminAmenityController::class, 'index'])->name('amenities');
    Route::post('/amenities', [AdminAmenityController::class, 'store'])->name('amenities.store');
    Route::put('/amenities/{amenity}', [AdminAmenityController::class, 'update'])->name('amenities.update');
    Route::put('/amenities/{amenity}/approve', [AdminAmenityController::class, 'approve'])->name('amenities.approve');
    Route::delete('/amenities/{amenity}', [AdminAmenityController::class, 'destroy'])->name('amenities.destroy');
    
    // Posts
    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [AdminPostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [AdminPostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [AdminPostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [AdminPostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [AdminPostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/upload-image', [AdminPostController::class, 'uploadEditorImage'])->name('posts.upload-image');
    
    Route::get('/promotions', [AdminDataController::class, 'promotions'])->name('promotions');
    
    Route::get('/blogs', [AdminDataController::class, 'blogs'])->name('blogs');
    Route::get('/reviews', [AdminDataController::class, 'reviews'])->name('reviews');
    Route::delete('/reviews/{review}', [AdminDataController::class, 'destroyReview'])->name('reviews.destroy');

    // Payouts (quản lý yêu cầu rút tiền từ host)
    Route::get('/payouts', [\App\Http\Controllers\Admin\AdminPayoutController::class, 'index'])->name('payouts');
    Route::put('/payouts/{payout}/approve', [\App\Http\Controllers\Admin\AdminPayoutController::class, 'approve'])->name('payouts.approve');
    Route::put('/payouts/{payout}/reject', [\App\Http\Controllers\Admin\AdminPayoutController::class, 'reject'])->name('payouts.reject');
});


Route::get('/generate-qr', [PaymentController::class, 'generateQR'])->name('generate.qr');
Route::get('/proxy-qr', [PaymentController::class, 'proxyQR'])->name('proxy.qr');
