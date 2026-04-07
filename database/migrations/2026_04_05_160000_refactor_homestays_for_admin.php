<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homestays', function (Blueprint $table) {
            // Drop host_id foreign key and column
            $table->dropForeign(['host_id']);
            $table->dropColumn('host_id');
            
            // Add new columns
            $table->string('room_code', 20)->unique()->after('id');
            $table->string('slug', 255)->unique()->after('title');
            $table->string('type', 100)->nullable()->after('slug');
            $table->string('district', 100)->nullable()->after('province');
            $table->string('ward', 100)->nullable()->after('district');
        });

        // Update status enum - change from approve workflow to publish workflow
        DB::statement("ALTER TABLE homestays MODIFY COLUMN status ENUM('published', 'draft') DEFAULT 'draft'");
        
        // Update existing records
        DB::table('homestays')
            ->whereIn('status', ['approved', 'available'])
            ->update(['status' => 'published']);
            
        DB::table('homestays')
            ->whereIn('status', ['pending', 'rejected', 'unavailable'])
            ->update(['status' => 'draft']);

        // Remove approval fields
        Schema::table('homestays', function (Blueprint $table) {
            $table->dropColumn(['approved_at', 'rejected_at', 'rejection_reason']);
        });
    }

    public function down(): void
    {
        Schema::table('homestays', function (Blueprint $table) {
            $table->foreignId('host_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->dropColumn(['room_code', 'slug', 'type', 'district', 'ward']);
        });

        DB::statement("ALTER TABLE homestays MODIFY COLUMN status ENUM('available', 'unavailable', 'pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
