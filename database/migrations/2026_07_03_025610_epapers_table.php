<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('epapers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('edition_date')->unique();
            $table->string('cover_image')->nullable();
            $table->string('file_path'); // PDF edisi cetak
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index('edition_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('epapers');
    }
};