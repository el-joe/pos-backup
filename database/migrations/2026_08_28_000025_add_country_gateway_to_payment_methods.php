<?php

use App\Models\Country;
use App\Models\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->json('supported_countries')->nullable()->after('details');
            $table->enum('gateway_type', ['stripe', 'paymob', 'manual', 'other'])->default('other')->after('provider');
            $table->boolean('is_active')->default(1)->after('gateway_type');
        });

        $egyptId = Country::where('code', 'EG')->value('id');

        PaymentMethod::updateOrCreate(['provider' => 'stripe'], [
            'name' => 'Stripe',
            'gateway_type' => 'stripe',
            'manual' => false,
            'required_fields' => ['secret_key', 'publishable_key', 'webhook_secret'],
            'supported_countries' => null,
            'active' => true,
        ]);

        PaymentMethod::updateOrCreate(['provider' => 'paymob'], [
            'name' => 'Paymob',
            'gateway_type' => 'paymob',
            'manual' => false,
            'required_fields' => ['api_key', 'integration_id', 'iframe_id', 'hmac_secret'],
            'supported_countries' => $egyptId ? json_encode([$egyptId]) : null,
            'active' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn(['supported_countries', 'gateway_type', 'is_active']);
        });
    }
};
