<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds the explicit depreciation_basis selector ('useful_life' | 'rate') and backfills it
     * for existing rows where exactly one of useful_life_months/depreciation_rate is set.
     * Rows carrying BOTH (a contradictory state, e.g. FA-000003/FA-000004) are never guessed at
     * here — they are left with depreciation_basis = null and reported so an operator can
     * resolve them deliberately (see prompt 09).
     */
    public function up(): void
    {
        Schema::table('fixed_assets', function (Blueprint $table) {
            $table->string('depreciation_basis')->nullable()->after('depreciation_rate');
        });

        $assets = DB::table('fixed_assets')->select('id', 'code', 'useful_life_months', 'depreciation_rate')->get();

        $conflicting = [];

        foreach ($assets as $asset) {
            $hasLife = (int) ($asset->useful_life_months ?? 0) > 0;
            $hasRate = (float) ($asset->depreciation_rate ?? 0) > 0;

            if ($hasLife && $hasRate) {
                $conflicting[] = $asset->code ?? $asset->id;
                continue;
            }

            if ($hasLife) {
                DB::table('fixed_assets')->where('id', $asset->id)->update(['depreciation_basis' => 'useful_life']);
            } elseif ($hasRate) {
                DB::table('fixed_assets')->where('id', $asset->id)->update(['depreciation_basis' => 'rate']);
            }
        }

        if (!empty($conflicting)) {
            Log::warning('Fixed assets with contradictory depreciation bases (both useful_life_months and depreciation_rate set) require manual resolution.', [
                'codes' => $conflicting,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('fixed_assets', function (Blueprint $table) {
            $table->dropColumn('depreciation_basis');
        });
    }
};
