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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('source_id');
            $table->string('title');
            $table->text('content');
            $table->string('category');
            $table->text('author')->nullable();
            $table->string('source_name');
            $table->dateTime('published_at');
            $table->string('url')->unique();
            $table->timestamps();
            $table->index(['source_id', 'category', 'published_at']);
            $table->fullText(['title', 'content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
