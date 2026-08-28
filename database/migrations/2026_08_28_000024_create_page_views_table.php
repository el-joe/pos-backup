<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central')->create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500);
            $table->string('referrer', 500)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('country_code', 5)->nullable();
            $table->boolean('is_bot')->default(false);
            $table->string('session_id', 64)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('path');
            $table->index('created_at');
            $table->index('country_code');
        });
    }

    public function down(): void
    {
        Schema::connection('central')->dropIfExists('page_views');
    }
};
