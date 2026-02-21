<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->text('address')->nullable();
            $table->uuid('referral_code')->unique();
            $table->decimal('commission_rate', 5, 2)->default(0.00);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->unsignedBigInteger('partner_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
