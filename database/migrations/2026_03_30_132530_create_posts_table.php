<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 300);
            $table->string('slug', 300)->unique();
            $table->string('thumbnail_url', 500)->nullable();
            $table->longText('content');
            $table->string('category', 100)->nullable();
            $table->enum('status', ['draft', 'published', 'hidden'])->default('draft');
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
