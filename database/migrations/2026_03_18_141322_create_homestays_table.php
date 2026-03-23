<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homestays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 200);
            $table->text('description');
            $table->text('address');
            $table->string('province', 100);
            $table->decimal('price_per_night', 12, 2);
            $table->tinyInteger('max_guests');
            $table->tinyInteger('num_bedrooms');
            $table->tinyInteger('num_beds');
            $table->tinyInteger('num_bathrooms');
            $table->time('check_in_time')->default('14:00:00');
            $table->time('check_out_time')->default('12:00:00');
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();
            $table->enum('cancellation_policy', ['flexible', 'moderate', 'strict'])->default('moderate');
            $table->enum('status', ['draft', 'pending', 'active', 'inactive'])->default('draft');
            $table->decimal('avg_rating', 3, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homestays');
    }
};
