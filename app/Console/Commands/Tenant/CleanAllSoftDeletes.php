<?php

namespace App\Console\Commands\Tenant;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CleanAllSoftDeletes extends Command
{
    protected $signature = 'cleanup:softdeletes:all {--days=7 : Delete soft deleted older than X days}';
    protected $description = 'Automatically delete soft deleted records older than X days for all models with SoftDeletes';

    public function handle()
    {
        $days = $this->option('days') ?? 7;

        foreach(Tenant::all() as $tenant){
            tenancy()->initialize($tenant);
            $this->info("Processing tenant: " . $tenant->name);
            $this->cleanSoftDeletes($days);
            tenancy()->end();
        }
    }

    protected function cleanSoftDeletes($days)
    {
        $deletedCount = 0;

        // احصل على كل الموديلات داخل app/Models
        $modelsPath = app_path('Models/Tenant');
        $modelFiles = scandir($modelsPath);

        foreach ($modelFiles as $file) {
            if (Str::endsWith($file, '.php')) {
                $modelClass = 'App\\Models\\Tenant\\' . pathinfo($file, PATHINFO_FILENAME);

                if (class_exists($modelClass)) {
                    $model = new $modelClass;

                    // تحقق إذا الموديل فيه SoftDeletes
                    if (in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', class_uses($model))) {
                        $count = $model::onlyTrashed()
                            ->where('deleted_at', '<', Carbon::now()->subDays($days))
                            ->forceDelete();

                        $deletedCount += $count;
                        $this->info("Cleaned $count records from $modelClass");
                    }
                }
            }
        }

        $this->info("Total deleted records: $deletedCount");
    }
}
