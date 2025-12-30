<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();

            $table->string('title_en');
            $table->string('title_ar')->nullable();

            $table->text('short_description_en')->nullable();
            $table->text('short_description_ar')->nullable();

            $table->longText('content_en');
            $table->longText('content_ar')->nullable();

            $table->boolean('is_published')->default(true)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
