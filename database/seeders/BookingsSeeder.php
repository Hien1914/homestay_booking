<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Homestay;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingsSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role', 'user')->orderBy('id')->get()->values();
        $homestays = Homestay::where('is_approved', true)->orderBy('id')->get()->values();

        if ($users->isEmpty() || $homestays->isEmpty()) {
            return;
        }

        $bookings = [
            ['user' => 0, 'homestay' => 0, 'check_in' => now()->subDays(30), 'nights' => 3, 'guests' => 2, 'status' => Booking::STATUS_COMPLETED],
            ['user' => 0, 'homestay' => 3, 'check_in' => now()->subDays(24), 'nights' => 2, 'guests' => 4, 'status' => Booking::STATUS_COMPLETED],
            ['user' => 0, 'homestay' => 5, 'check_in' => now()->subDays(18), 'nights' => 2, 'guests' => 3, 'status' => Booking::STATUS_COMPLETED],
            ['user' => 1, 'homestay' => 1, 'check_in' => now()->subDays(22), 'nights' => 2, 'guests' => 2, 'status' => Booking::STATUS_COMPLETED],
            ['user' => 1, 'homestay' => 6, 'check_in' => now()->subDays(16), 'nights' => 3, 'guests' => 3, 'status' => Booking::STATUS_COMPLETED],
            ['user' => 2, 'homestay' => 2, 'check_in' => now()->subDays(20), 'nights' => 2, 'guests' => 2, 'status' => Booking::STATUS_COMPLETED],
            ['user' => 2, 'homestay' => 8, 'check_in' => now()->subDays(10), 'nights' => 2, 'guests' => 2, 'status' => Booking::STATUS_COMPLETED],
            ['user' => 3, 'homestay' => 4, 'check_in' => now()->subDays(14), 'nights' => 3, 'guests' => 4, 'status' => Booking::STATUS_COMPLETED],
            ['user' => 4, 'homestay' => 7, 'check_in' => now()->subDays(12), 'nights' => 2, 'guests' => 2, 'status' => Booking::STATUS_COMPLETED],
            ['user' => 5, 'homestay' => 9, 'check_in' => now()->subDays(8), 'nights' => 2, 'guests' => 2, 'status' => Booking::STATUS_COMPLETED],
            ['user' => 6, 'homestay' => 2, 'check_in' => now()->addDays(2), 'nights' => 2, 'guests' => 2, 'status' => Booking::STATUS_CONFIRMED],
            ['user' => 7, 'homestay' => 6, 'check_in' => now()->addDays(4), 'nights' => 3, 'guests' => 3, 'status' => Booking::STATUS_CONFIRMED],
            ['user' => 8, 'homestay' => 0, 'check_in' => now()->addDays(1), 'nights' => 4, 'guests' => 5, 'status' => Booking::STATUS_CHECKED_IN],
            ['user' => 9, 'homestay' => 3, 'check_in' => now()->addDays(12), 'nights' => 2, 'guests' => 2, 'status' => Booking::STATUS_PENDING],
            ['user' => 1, 'homestay' => 4, 'check_in' => now()->addDays(15), 'nights' => 3, 'guests' => 2, 'status' => Booking::STATUS_PENDING],
            ['user' => 3, 'homestay' => 8, 'check_in' => now()->subDays(5), 'nights' => 2, 'guests' => 2, 'status' => Booking::STATUS_CANCELLED],
            ['user' => 5, 'homestay' => 1, 'check_in' => now()->subDays(3), 'nights' => 2, 'guests' => 2, 'status' => Booking::STATUS_CANCELLED],
            ['user' => 7, 'homestay' => 5, 'check_in' => now()->subDays(1), 'nights' => 3, 'guests' => 4, 'status' => Booking::STATUS_CONFIRMED],
        ];

        foreach ($bookings as $item) {
            $user = $users->get($item['user']);
            $homestay = $homestays->get($item['homestay']);

            if (!$user || !$homestay) {
                continue;
            }

            $checkIn = Carbon::parse($item['check_in'])->startOfDay();
            $checkOut = (clone $checkIn)->addDays($item['nights']);
            $totalAmount = $homestay->price_per_night * $item['nights'];

            Booking::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'homestay_id' => $homestay->id,
                    'check_in' => $checkIn->toDateString(),
                ],
                [
                    'check_out' => $checkOut->toDateString(),
                    'num_guests' => $item['guests'],
                    'total_amount' => $totalAmount,
                    'admin_earn' => (int) round($totalAmount * 0.12),
                    'host_earn' => (int) round($totalAmount * 0.88),
                    'status' => $item['status'],
                    'cancel_reason' => $item['status'] === Booking::STATUS_CANCELLED ? 'Khách thay đổi lịch trình cá nhân.' : null,
                    'cancel_status' => $item['status'] === Booking::STATUS_CANCELLED ? 'approved' : 'none',
                    'cancel_requested_at' => $item['status'] === Booking::STATUS_CANCELLED ? $checkIn->copy()->subDays(2) : null,
                    'host_approved' => $item['status'] === Booking::STATUS_CANCELLED ? true : null,
                    'created_at' => $checkIn->copy()->subDays(5),
                    'updated_at' => $checkIn->copy()->subDays(2),
                ]
            );
        }
    }
}
