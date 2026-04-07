<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('homestay_id')->constrained('homestays')->onDelete('cascade');
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedTinyInteger('num_guests');
            $table->decimal('total_amount', 12, 2);
            $table->foreignId('promotion_id')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->enum('payment_method', ['vnpay', 'momo', 'bank_transfer'])->nullable();
            $table->enum('payment_status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id', 100)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
