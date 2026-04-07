<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('homestay_amenities', function (Blueprint $table) {
            $table->foreignId('homestay_id')->constrained('homestays')->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained('amenities')->onDelete('cascade');
            $table->primary(['homestay_id', 'amenity_id']);
            $table->integer('quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homestay_amenities');
    }
};