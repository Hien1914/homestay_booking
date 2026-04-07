<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homestay_amenities', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('homestay_amenities', function (Blueprint $table) {
            $table->integer('quantity')->change();
        });
    }
};
