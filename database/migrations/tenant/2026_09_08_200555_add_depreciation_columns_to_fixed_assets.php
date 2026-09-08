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
        Schema::table('fixed_assets', function (Blueprint $table) {
            $table->decimal('accumulated_depreciation', 15, 2)->default(0)->after('salvage_value');
            $table->date('last_depreciated_on')->nullable()->after('accumulated_depreciation');
            $table->timestamp('disposed_at')->nullable()->after('last_depreciated_on');
            $table->decimal('disposal_proceeds', 15, 2)->nullable()->after('disposed_at');
        });

        // A dedicated depreciation schedule/history table — kept separate from
        // fixed_asset_extensions, which already means something else (capital
        // improvements that add cost and extend useful life).
        Schema::create('fixed_asset_depreciation_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixed_asset_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('transaction_id')->nullable()->index();
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('accumulated_depreciation_after', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['fixed_asset_id', 'period_year', 'period_month'], 'fa_depreciation_period_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_asset_depreciation_entries');

        Schema::table('fixed_assets', function (Blueprint $table) {
            $table->dropColumn(['accumulated_depreciation', 'last_depreciated_on', 'disposed_at', 'disposal_proceeds']);
        });
    }
};
