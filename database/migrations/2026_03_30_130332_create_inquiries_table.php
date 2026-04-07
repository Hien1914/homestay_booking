<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('homestay_id')->constrained('homestays')->onDelete('cascade');
            $table->text('message');
            $table->enum('status', ['pending', 'replied'])->default('pending');
            $table->text('host_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
