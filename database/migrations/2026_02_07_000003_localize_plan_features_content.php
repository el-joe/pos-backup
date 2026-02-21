<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('plan_features')) {
            return;
        }

        Schema::table('plan_features', function (Blueprint $table) {
            if (!Schema::hasColumn('plan_features', 'content_ar')) {
                $table->string('content_ar')->nullable()->after('value');
            }
            if (!Schema::hasColumn('plan_features', 'content_en')) {
                $table->string('content_en')->nullable()->after('content_ar');
            }
        });

        if (Schema::hasColumn('plan_features', 'content')) {
            DB::table('plan_features')
                ->whereNotNull('content')
                ->update([
                    'content_ar' => DB::raw('COALESCE(content_ar, content)'),
                    'content_en' => DB::raw('COALESCE(content_en, content)'),
                ]);

            Schema::table('plan_features', function (Blueprint $table) {
                $table->dropColumn('content');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('plan_features')) {
            return;
        }

        if (!Schema::hasColumn('plan_features', 'content')) {
            Schema::table('plan_features', function (Blueprint $table) {
                $table->string('content')->nullable()->after('value');
            });

            DB::table('plan_features')
                ->whereNotNull('content_en')
                ->update([
                    'content' => DB::raw('content_en'),
                ]);
        }

        Schema::table('plan_features', function (Blueprint $table) {
            if (Schema::hasColumn('plan_features', 'content_ar')) {
                $table->dropColumn('content_ar');
            }
            if (Schema::hasColumn('plan_features', 'content_en')) {
                $table->dropColumn('content_en');
            }
        });
    }
};
