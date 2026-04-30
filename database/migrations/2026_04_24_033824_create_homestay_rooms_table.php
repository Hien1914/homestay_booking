<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('homestay_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homestay_id')->constrained('homestays')->onDelete('cascade');
            $table->string('feature_type', 50);
            $table->integer('quantity')->default(1);
            $table->unique(['homestay_id', 'feature_type']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homestay_rooms');
    }
};