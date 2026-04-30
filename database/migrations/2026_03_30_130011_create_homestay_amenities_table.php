<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('homestay_amenities', function (Blueprint $table) {
            $table->foreignId('homestay_id')->constrained('homestays')->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained('amenities')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->primary(['homestay_id', 'amenity_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('homestay_amenities');
    }
};