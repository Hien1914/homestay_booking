<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained('users')->onDelete('cascade');
            $table->dropForeign(['homestay_id']);
            $table->dropColumn('homestay_id');
        });

        Schema::table('homestays', function (Blueprint $table) {
            $table->foreignId('promotion_id')->nullable()->after('max_guests')->constrained('promotions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homestays', function (Blueprint $table) {
            $table->dropForeign(['promotion_id']);
            $table->dropColumn('promotion_id');
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->foreignId('homestay_id')->after('id')->nullable()->constrained('homestays')->onDelete('cascade');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
