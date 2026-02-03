<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->text('description')->nullable();
            $table->date('date');
            $table->year('year');
            $table->integer('days')->default(1);
            $table->boolean('is_recurring')->default(false); // yearly recurring
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete(); // null = all branches
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
