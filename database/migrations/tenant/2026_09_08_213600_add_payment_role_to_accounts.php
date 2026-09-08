<?php

use App\Enums\AccountTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('is_payment_capable')->default(false)->index()->after('type');
        });

        $paymentCapableTypes = array_values(array_filter(
            array_map(fn(AccountTypeEnum $case) => $case->isPaymentCapable() ? $case->value : null, AccountTypeEnum::cases())
        ));

        DB::table('accounts')->whereIn('type', $paymentCapableTypes)->update(['is_payment_capable' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('is_payment_capable');
        });
    }
};
