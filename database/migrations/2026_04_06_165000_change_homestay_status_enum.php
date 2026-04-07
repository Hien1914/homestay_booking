<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Chuyển status ENUM thành available/unavailable cho trạng thái phòng
        DB::statement("ALTER TABLE homestays MODIFY COLUMN status ENUM('available', 'unavailable') DEFAULT 'available'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE homestays MODIFY COLUMN status ENUM('published', 'draft') DEFAULT 'draft'");
    }
};
