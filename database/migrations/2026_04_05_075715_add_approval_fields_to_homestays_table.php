<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify status column to support approval workflow
        DB::statement("ALTER TABLE homestays MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'available', 'unavailable', 'draft') DEFAULT 'pending'");
        
        Schema::table('homestays', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
        });
    }

    public function down(): void
    {
        Schema::table('homestays', function (Blueprint $table) {
            $table->dropColumn(['approved_at', 'rejected_at', 'rejection_reason']);
        });
        
        DB::statement("ALTER TABLE homestays MODIFY COLUMN status ENUM('available', 'unavailable') DEFAULT 'available'");
    }
};
