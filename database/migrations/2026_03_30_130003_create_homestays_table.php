<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('homestays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('destination_id')->nullable()->constrained('destinations')->onDelete('set null');
            $table->string('title', 255);
            $table->string('slug', 255)->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('address', 255);
            $table->string('ward', 100)->nullable();
            $table->string('province', 100);
            $table->bigInteger('price_per_night');
            $table->tinyInteger('max_guests');
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homestays');
    }
};