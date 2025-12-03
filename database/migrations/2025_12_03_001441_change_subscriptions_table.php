<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `subscriptions` CHANGE `payment_details` `payment_details` JSON NULL, CHANGE `payment_callback_details` `payment_callback_details` JSON NULL;");
        DB::statement("ALTER TABLE `subscriptions` CHANGE `tenant_id` `tenant_id` VARCHAR(255) NOT NULL;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
