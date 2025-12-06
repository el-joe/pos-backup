<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:customers-due-amounts-alert')->weeklyOn(Carbon::FRIDAY, '23:00');
Schedule::command('app:supplier-due-amounts-alert')->weeklyOn(Carbon::FRIDAY, '23:00');
Schedule::command('app:sales-summary-report')->dailyAt('23:55');
// Schedule::command('cleanup:softdeletes:all --days=10')->dailyAt('23:55');
