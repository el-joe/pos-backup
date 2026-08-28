<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_systems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->string('system_slug', 50);
            $table->timestamps();
            $table->unique(['subscription_id', 'system_slug']);
            $table->index(['system_slug']);
        });

        $subscriptions = DB::table('subscriptions')->get(['id', 'systems_allowed']);
        foreach ($subscriptions as $sub) {
            $systems = json_decode($sub->systems_allowed, true) ?? [];
            foreach ($systems as $slug) {
                DB::table('subscription_systems')->insertOrIgnore([
                    'subscription_id' => $sub->id,
                    'system_slug' => $slug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_systems');
    }
};
