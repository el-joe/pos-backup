<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (! Schema::hasColumn('tenants', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (! Schema::hasColumn('tenants', 'email')) {
                $table->string('email', 191)->nullable()->after('name');
            }
            if (! Schema::hasColumn('tenants', 'phone')) {
                $table->string('phone', 50)->nullable()->after('email');
            }
            if (! Schema::hasColumn('tenants', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('tenants', 'currency_id')) {
                $table->unsignedBigInteger('currency_id')->nullable()->after('country_id');
            }
            if (! Schema::hasColumn('tenants', 'tax_number')) {
                $table->string('tax_number', 100)->nullable()->after('currency_id');
            }
            if (! Schema::hasColumn('tenants', 'active')) {
                $table->boolean('active')->default(true)->after('tax_number');
            }
        });

        DB::statement("UPDATE tenants SET
            name       = JSON_UNQUOTE(JSON_EXTRACT(data, '$.name')),
            email      = JSON_UNQUOTE(JSON_EXTRACT(data, '$.email')),
            phone      = JSON_UNQUOTE(JSON_EXTRACT(data, '$.phone')),
            country_id = JSON_UNQUOTE(JSON_EXTRACT(data, '$.country_id')),
            currency_id= JSON_UNQUOTE(JSON_EXTRACT(data, '$.currency_id')),
            tax_number = JSON_UNQUOTE(JSON_EXTRACT(data, '$.tax_number')),
            active     = IF(
                             JSON_UNQUOTE(JSON_EXTRACT(data, '$.active')) IN ('false', '0'),
                             0,
                             1
                         )
        WHERE data IS NOT NULL");

        if (! $this->indexExists('tenants', 'tenants_email_index')) {
            Schema::table('tenants', fn (Blueprint $table) => $table->index(['email']));
        }
        if (! $this->indexExists('tenants', 'tenants_active_country_id_index')) {
            Schema::table('tenants', fn (Blueprint $table) => $table->index(['active', 'country_id']));
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return collect(Schema::getIndexes($table))->contains('name', $indexName);
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['active', 'country_id']);
            $table->dropColumn([
                'name',
                'email',
                'phone',
                'country_id',
                'currency_id',
                'tax_number',
                'active',
            ]);
        });
    }
};
