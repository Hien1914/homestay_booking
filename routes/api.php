<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HomestayController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Admin\AdminApiController;
use Illuminate\Support\Facades\Route;

/* ── Auth ── */

Route::prefix('auth')->group(function () {
    Route::post('/register',        [AuthController::class, 'register']);
    Route::post('/login',           [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password',  [AuthController::class, 'resetPassword']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

/* ── Public & Authenticated Homestays ── */
Route::prefix('homestays')->group(function () {
    // Public endpoints
    Route::get('/', [HomestayController::class, 'index']);
    Route::get('/{homestay}', [HomestayController::class, 'show']);
    Route::get('/{homestay}/availability', [HomestayController::class, 'availability']);
    Route::get('/{homestay}/reviews', [HomestayController::class, 'reviews']);
    Route::get('/{homestay}/bookings', [HomestayController::class, 'bookings']);
    
    // Authenticated endpoints (create, update, delete)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [HomestayController::class, 'store']);
        Route::put('/{homestay}', [HomestayController::class, 'update']);
        Route::delete('/{homestay}', [HomestayController::class, 'destroy']);
    });
});

/* ── Authenticated ── */
Route::middleware('auth:sanctum')->group(function () {
    
    // User's homestays
    Route::get('/user/homestays', [HomestayController::class, 'userHomestays']);

    // User profile
    Route::get('/user/profile',      [UserController::class, 'profile']);
    Route::put('/user/profile',      [UserController::class, 'updateProfile']);
    Route::post('/user/avatar',      [UserController::class, 'uploadAvatar']);

    // Bookings
    Route::post('/bookings/calculate',       [BookingController::class, 'calculate']);
    Route::post('/bookings',                 [BookingController::class, 'store']);
    Route::get('/bookings/{booking}',        [BookingController::class, 'show']);
    Route::put('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    Route::get('/user/bookings',             [BookingController::class, 'myBookings']);

    // Payments
    Route::post('/payments/vnpay/create',    [PaymentController::class, 'createVnpay']);
    Route::post('/payments/momo/create',     [PaymentController::class, 'createMomo']);

    // Reviews
    Route::post('/reviews',                  [ReviewController::class, 'store']);
    Route::put('/reviews/{review}',          [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}',       [ReviewController::class, 'destroy']);

    // Wishlists
    Route::get('/wishlists',                         [WishlistController::class, 'index']);
    Route::post('/wishlists',                        [WishlistController::class, 'store']);
    Route::delete('/wishlists/{homestayId}',         [WishlistController::class, 'destroy']);

    // Chat
    Route::post('/chat/start',                                  [ChatController::class, 'start']);
    Route::post('/chat/{conversation}/message',                 [ChatController::class, 'sendMessage']);
    Route::get('/chat/{conversation}/messages',                 [ChatController::class, 'messages']);

    // Tickets
    Route::get('/tickets',                           [TicketController::class, 'index']);
    Route::post('/tickets',                          [TicketController::class, 'store']);
    Route::get('/tickets/{ticket}',                  [TicketController::class, 'show']);
    Route::post('/tickets/{ticket}/replies',         [TicketController::class, 'reply']);
    Route::put('/tickets/{ticket}/close',            [TicketController::class, 'close']);
});

/* ── Admin ── */
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users',                                [AdminApiController::class, 'users']);
    Route::put('/users/{user}/toggle',                  [AdminApiController::class, 'toggleUser']);
    Route::get('/homestays/pending',                    [AdminApiController::class, 'pendingHomestays']);
    Route::put('/homestays/{homestay}/approve',         [AdminApiController::class, 'approveHomestay']);
    Route::put('/homestays/{homestay}/reject',          [AdminApiController::class, 'rejectHomestay']);
    Route::get('/stats',                                [AdminApiController::class, 'stats']);
    Route::get('/tickets',                              [TicketController::class, 'adminIndex']);
    Route::put('/tickets/{ticket}/assign',              [TicketController::class, 'assign']);
    Route::put('/tickets/{ticket}/close',               [TicketController::class, 'adminClose']);
});

/* ── Webhooks (public, verify signature bên trong) ── */
Route::post('/payments/vnpay/return', [PaymentController::class, 'vnpayReturn']);
Route::post('/payments/vnpay/ipn',    [PaymentController::class, 'vnpayIpn']);
Route::post('/payments/momo/ipn',     [PaymentController::class, 'momoIpn']);