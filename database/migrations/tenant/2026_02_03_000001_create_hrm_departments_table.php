<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('ar_name')->nullable();
                $table->text('description')->nullable();
                $table->text('ar_description')->nullable();
                $table->foreignId('parent_id')->nullable()->constrained('departments')->nullOnDelete();
                $table->foreignId('manager_id')->nullable()->index();
                $table->boolean('active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
