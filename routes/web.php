<?php

use App\Http\Controllers\Client\HomestayDetailController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\RoomSearchController;
use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('clients/home');
}) -> name('home');

Route::get('/diem-den/{category?}', function (?string $category = 'ven-bien') {
    $destinationsByCategory = [
        'ven-bien' => [
            ['name' => 'Đà Nẵng', 'description' => 'Bãi biển đẹp, tiện nghi đa dạng, phù hợp nghỉ dưỡng ngắn ngày.', 'tag' => 'Biển'],
            ['name' => 'Nha Trang', 'description' => 'Không khí sôi động, nhiều lựa chọn homestay gần biển.', 'tag' => 'Biển'],
            ['name' => 'Phú Quốc', 'description' => 'Thiên đường nghỉ dưỡng với bãi cát dài và nước trong xanh.', 'tag' => 'Biển'],
            ['name' => 'Vũng Tàu', 'description' => 'Dễ di chuyển cuối tuần, phù hợp nhóm bạn và gia đình.', 'tag' => 'Biển'],
        ],
        'mien-nui' => [
            ['name' => 'Sapa', 'description' => 'Khí hậu mát lạnh, cảnh núi hùng vĩ và nhiều homestay view cao.', 'tag' => 'Núi'],
            ['name' => 'Mộc Châu', 'description' => 'Đồi chè và không gian yên bình, hợp nghỉ dưỡng thư giãn.', 'tag' => 'Núi'],
            ['name' => 'Hà Giang', 'description' => 'Cung đường đèo đẹp, hợp người thích khám phá.', 'tag' => 'Núi'],
            ['name' => 'Tam Đảo', 'description' => 'Điểm đến gần Hà Nội, thời tiết mát mẻ quanh năm.', 'tag' => 'Núi'],
        ],
        'thanh-thi' => [
            ['name' => 'Hà Nội', 'description' => 'Ẩm thực phong phú, nhiều homestay trung tâm thuận tiện đi lại.', 'tag' => 'Thành thị'],
            ['name' => 'TP.HCM', 'description' => 'Năng động, nhiều lựa chọn lưu trú theo ngân sách.', 'tag' => 'Thành thị'],
            ['name' => 'Đà Nẵng', 'description' => 'Thành phố sạch đẹp, kết nối tốt giữa biển và trung tâm.', 'tag' => 'Thành thị'],
            ['name' => 'Hải Phòng', 'description' => 'Phù hợp cho chuyến đi kết hợp công việc và nghỉ ngơi.', 'tag' => 'Thành thị'],
        ],
        'ho-song' => [
            ['name' => 'Đà Lạt', 'description' => 'Hồ Xuân Hương và khí hậu dịu mát, hợp các cặp đôi.', 'tag' => 'Hồ & sông'],
            ['name' => 'Ninh Bình', 'description' => 'Non nước hữu tình, cảnh sông núi đặc trưng miền Bắc.', 'tag' => 'Hồ & sông'],
            ['name' => 'Cần Thơ', 'description' => 'Văn hóa sông nước miền Tây, trải nghiệm chợ nổi độc đáo.', 'tag' => 'Hồ & sông'],
            ['name' => 'Huế', 'description' => 'Dọc bờ sông Hương, không gian trầm lắng và thư thái.', 'tag' => 'Hồ & sông'],
        ],
        'sang-trong' => [
            ['name' => 'Phú Quốc Premium', 'description' => 'Biệt thự hồ bơi riêng, dịch vụ cao cấp.', 'tag' => 'Sang trọng'],
            ['name' => 'Hạ Long Luxury', 'description' => 'Không gian riêng tư, tiện ích chuẩn nghỉ dưỡng.', 'tag' => 'Sang trọng'],
            ['name' => 'Đà Nẵng Signature', 'description' => 'Nội thất hiện đại, vị trí đắc địa gần biển.', 'tag' => 'Sang trọng'],
            ['name' => 'Nha Trang Elite', 'description' => 'View biển rộng, tiêu chuẩn lưu trú cao.', 'tag' => 'Sang trọng'],
        ],
    ];

    $categoryLabels = [
        'ven-bien' => 'Ven biển',
        'mien-nui' => 'Miền núi',
        'thanh-thi' => 'Thành thị',
        'ho-song' => 'Hồ & sông',
        'sang-trong' => 'Sang trọng',
    ];

    if (!array_key_exists($category, $destinationsByCategory)) {
        abort(404);
    }

    return view('clients.destinations.index', [
        'category' => $category,
        'categoryLabels' => $categoryLabels,
        'destinations' => $destinationsByCategory[$category],
    ]);
})->name('destinations.show');

Route::get('/tim-phong', [RoomSearchController::class, 'index'])->name('rooms.search');
Route::get('/phong/{id}', [HomestayDetailController::class, 'show'])->name('homestay.show');
Route::get('/phong/{id}/dat-phong', fn (string $id) => redirect('/phong/' . $id . '#dat-phong', 301))->name('homestay.booking');
Route::post('/phong/{id}/dat-phong/demo', [PaymentController::class, 'storeDemoBooking'])->name('homestay.booking.demo');
Route::get('/thanh-toan', [PaymentController::class, 'show'])->name('payment.show');

Route::get('/dang-nhap', function () {
    return view('clients.auth.login');
})->name('login.page');

Route::get('/thong-tin-ca-nhan', function () {
    return view('clients.profile.show');
})->name('profile.page');

// ── Admin Auth ──────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',      [AdminAuthController::class, 'showLogin'])    ->name('login');
    Route::post('/login',     [AdminAuthController::class, 'loginAccount']) ->name('login.account');
    Route::post('/login-key', [AdminAuthController::class, 'loginKey'])     ->name('login.key');
    Route::post('/logout',    [AdminAuthController::class, 'logout'])       ->name('logout');
});

// ── Admin Pages ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('admin.key')->group(function () {
    Route::get('/',          fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', fn() => view('admin.dashboard'))  ->name('dashboard');
    Route::get('/categories', fn() => view('admin.categories'))->name('categories');
    Route::get('/users',     fn() => view('admin.users'))      ->name('users');
    Route::get('/homestays', fn() => view('admin.homestays'))  ->name('homestays');
    Route::get('/bookings',  fn() => view('admin.bookings'))   ->name('bookings');
    Route::get('/promotions', fn() => view('admin.promotions'))->name('promotions');
    Route::get('/blogs',     fn() => view('admin.blogs'))      ->name('blogs');
    Route::get('/reviews',   fn() => view('admin.reviews'))    ->name('reviews');
    Route::get('/tickets',   fn() => view('admin.tickets'))    ->name('tickets');
    Route::get('/faqs',      fn() => view('admin.faqs'))       ->name('faqs');
});
