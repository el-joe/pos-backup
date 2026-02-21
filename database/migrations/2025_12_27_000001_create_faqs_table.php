<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();

            $table->string('question_en');
            $table->string('question_ar')->nullable();

            $table->longText('answer_en');
            $table->longText('answer_ar')->nullable();

            $table->boolean('is_published')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
