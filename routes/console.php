<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:customers-due-amounts-alert')->weeklyOn(Carbon::FRIDAY, '23:00');
Schedule::command('app:supplier-due-amounts-alert')->weeklyOn(Carbon::FRIDAY, '23:00');
Schedule::command('app:sales-summary-report')->dailyAt('23:55');
// Schedule::command('cleanup:softdeletes:all --days=10')->dailyAt('23:55');
Schedule::command('app:convert-currencies')->dailyAt('01:00');
Schedule::command('app:generate-sitemap')->daily();

Schedule::call(function () {
    DB::connection('central')->table('page_views')
        ->where('created_at', '<', now()->subDays(90))
        ->delete();
})->monthly();
