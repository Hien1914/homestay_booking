<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('homestay_id')->constrained('homestays')->onDelete('cascade');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('num_guests');
            $table->bigInteger('total_amount');
            $table->bigInteger('admin_earn')->default(0);
            $table->bigInteger('host_earn')->default(0);
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'completed', 'cancelled'])->default('pending');
            $table->text('cancel_reason')->nullable();
            $table->enum('cancel_status', ['none', 'pending', 'approved', 'rejected'])->default('none');
            $table->timestamp('cancel_requested_at')->nullable();
            $table->boolean('host_approved')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};