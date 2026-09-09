<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->timestamp('represented_at')->nullable()->after('cleared_at');
            $table->unsignedBigInteger('replaced_by_check_id')->nullable()->index()->after('represented_at');
            $table->decimal('bank_charge', 15, 4)->nullable()->after('amount');

            $table->foreign('replaced_by_check_id')->references('id')->on('checks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->dropForeign(['replaced_by_check_id']);
            $table->dropColumn(['represented_at', 'replaced_by_check_id', 'bank_charge']);
        });
    }
};
