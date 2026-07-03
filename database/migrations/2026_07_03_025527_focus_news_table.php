<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('focus_news', function (Blueprint $table) {
            $table->foreignId('focus_id')->constrained('focuses')->cascadeOnDelete();
            $table->foreignId('news_id')->constrained()->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->primary(['focus_id', 'news_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('focus_news');
    }
};