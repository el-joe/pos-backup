<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->enum('page_type', ['static', 'documentation'])->default('static')->after('is_published');
            $table->string('section', 100)->nullable()->after('page_type');
            $table->integer('sort_order')->default(0)->after('section');
            $table->json('youtube_videos')->nullable()->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['page_type', 'section', 'sort_order', 'youtube_videos']);
        });
    }
};
