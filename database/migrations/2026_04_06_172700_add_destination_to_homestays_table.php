<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homestays', function (Blueprint $table) {
            if (!Schema::hasColumn('homestays', 'destination_id')) {
                $table->foreignId('destination_id')
                    ->nullable()
                    ->constrained('destinations')
                    ->onDelete('set null')
                    ->after('ward');
            }
        });
    }

    public function down(): void
    {
        Schema::table('homestays', function (Blueprint $table) {
            if (Schema::hasColumn('homestays', 'destination_id')) {
                $table->dropConstrainedForeignId('destination_id');
            }
        });
    }
};
