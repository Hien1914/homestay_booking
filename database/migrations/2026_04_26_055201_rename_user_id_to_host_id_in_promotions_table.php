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
        // Dropping foreign key using raw SQL for better control
        try {
            DB::statement('ALTER TABLE promotions DROP FOREIGN KEY promotions_user_id_foreign');
        } catch (\Exception $e) {
            // Ignore if already dropped
        }
        
        DB::statement('ALTER TABLE promotions CHANGE user_id host_id BIGINT UNSIGNED NULL');
        
        Schema::table('promotions', function (Blueprint $table) {
            $table->foreign('host_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropForeign(['host_id']);
        });

        DB::statement('ALTER TABLE promotions CHANGE host_id user_id BIGINT UNSIGNED NULL');

        Schema::table('promotions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
