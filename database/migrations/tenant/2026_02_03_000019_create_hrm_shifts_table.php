<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('working_hours'); // in minutes
            $table->integer('break_duration')->default(0); // in minutes
            $table->integer('grace_period')->default(0); // late allowance in minutes
            $table->json('working_days')->nullable(); // [1,2,3,4,5] for Mon-Fri
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
