<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    public function run()
    {
        $bookings = Booking::orderBy('id')->get();

        foreach ($bookings as $booking) {
            $paymentStatus = match ($booking->status) {
                Booking::STATUS_COMPLETED, Booking::STATUS_CONFIRMED, Booking::STATUS_CHECKED_IN => Payment::STATUS_SUCCESS,
                Booking::STATUS_CANCELLED => Payment::STATUS_FAILED,
                default => Payment::STATUS_PENDING,
            };

            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'amount' => $booking->total_amount,
                    'payment_method' => 'bank_transfer',
                    'payment_status' => $paymentStatus,
                    'paid_at' => $paymentStatus === Payment::STATUS_SUCCESS
                        ? optional($booking->check_in)->copy()->subDays(3)
                        : null,
                    'created_at' => optional($booking->created_at)->copy() ?? now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
