<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 20)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('homestay_id')->constrained('homestays');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->tinyInteger('num_nights');
            $table->tinyInteger('num_guests');
            $table->decimal('price_per_night', 12, 2);
            $table->decimal('service_fee', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', [
                'pending',
                'confirmed',
                'checked_in',
                'checked_out',
                'completed',
                'cancelled',
                'rejected'
            ])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->text('special_requests')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->dateTime('actual_check_in')->nullable();
            $table->dateTime('actual_check_out')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
