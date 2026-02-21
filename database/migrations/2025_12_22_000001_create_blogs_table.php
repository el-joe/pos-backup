<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();

            $table->string('title_en');
            $table->string('title_ar')->nullable();

            $table->text('excerpt_en')->nullable();
            $table->text('excerpt_ar')->nullable();

            $table->longText('content_en');
            $table->longText('content_ar')->nullable();

            $table->string('image')->nullable();

            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
