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
            $table->string('name')->nullable()->after('id');
            $table->string('email', 191)->nullable()->after('name');
            $table->string('phone', 50)->nullable()->after('email');
            $table->unsignedBigInteger('country_id')->nullable()->after('phone');
            $table->unsignedBigInteger('currency_id')->nullable()->after('country_id');
            $table->string('tax_number', 100)->nullable()->after('currency_id');
            $table->boolean('active')->default(true)->after('tax_number');
        });

        DB::statement("UPDATE tenants SET
            name       = JSON_UNQUOTE(JSON_EXTRACT(data, '$.name')),
            email      = JSON_UNQUOTE(JSON_EXTRACT(data, '$.email')),
            phone      = JSON_UNQUOTE(JSON_EXTRACT(data, '$.phone')),
            country_id = JSON_UNQUOTE(JSON_EXTRACT(data, '$.country_id')),
            currency_id= JSON_UNQUOTE(JSON_EXTRACT(data, '$.currency_id')),
            tax_number = JSON_UNQUOTE(JSON_EXTRACT(data, '$.tax_number')),
            active     = COALESCE(JSON_UNQUOTE(JSON_EXTRACT(data, '$.active')), 1)
        WHERE data IS NOT NULL");

        Schema::table('tenants', fn (Blueprint $table) => $table->index(['email']));
        Schema::table('tenants', fn (Blueprint $table) => $table->index(['active', 'country_id']));
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
