<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Auto-cancel pending bookings at check-in date
        $schedule->call(function () {
            \App\Models\Booking::where('status', \App\Models\Booking::STATUS_PENDING)
                ->whereDate('check_in', '<=', today())
                ->get()
                ->each(function ($booking) {
                    $booking->update(['status' => \App\Models\Booking::STATUS_CANCELLED]);
                    if ($booking->payment) {
                        $booking->payment->update([
                            'payment_status' => \App\Models\Payment::STATUS_FAILED,
                        ]);
                    }
                });
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
