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
        Schema::table('homestays', function (Blueprint $table) {
            $table->dropColumn(['district', 'ward']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homestays', function (Blueprint $table) {
            $table->string('district', 100)->nullable()->after('province');
            $table->string('ward', 100)->nullable()->after('district');
        });
    }
};
