<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    public function run()
    {
        $comments = [
            ['rating' => 5, 'comment' => 'Chỗ ở rất sạch sẽ, chủ nhà thân thiện và hỗ trợ nhanh.'],
            ['rating' => 5, 'comment' => 'View đẹp hơn ảnh, buổi sáng cực chill và yên tĩnh.'],
            ['rating' => 4, 'comment' => 'Phòng đẹp, vị trí thuận tiện, sẽ quay lại vào dịp gần nhất.'],
            ['rating' => 5, 'comment' => 'Không gian ấm cúng, phù hợp gia đình nghỉ dưỡng cuối tuần.'],
            ['rating' => 4, 'comment' => 'Dịch vụ ổn, phòng thơm và gọn gàng, trải nghiệm tốt.'],
            ['rating' => 5, 'comment' => 'Cảnh quan tuyệt vời, check-in rất đẹp và riêng tư.'],
            ['rating' => 4, 'comment' => 'Nhân viên hỗ trợ nhiệt tình, giá hợp lý so với chất lượng.'],
            ['rating' => 5, 'comment' => 'Mọi thứ chỉn chu, từ giường ngủ tới khu sinh hoạt chung.'],
            ['rating' => 4, 'comment' => 'Ở thoải mái, gần các điểm tham quan, tiện di chuyển.'],
            ['rating' => 5, 'comment' => 'Một trong những homestay dễ chịu nhất mình từng ở.'],
        ];

        $completedBookings = Booking::where('status', Booking::STATUS_COMPLETED)
            ->orderBy('id')
            ->take(10)
            ->get();

        foreach ($completedBookings as $index => $booking) {
            $review = $comments[$index % count($comments)];

            Review::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'user_id' => $booking->user_id,
                    'homestay_id' => $booking->homestay_id,
                    'rating' => $review['rating'],
                    'comment' => $review['comment'],
                    'created_at' => optional($booking->check_out)->copy()->setTime(10, 0) ?? now(),
                ]
            );
        }
    }
}
