<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $modelsPath = app_path('Models/Tenant');
        $modelFiles = scandir($modelsPath);

        foreach ($modelFiles as $file) {
            if (Str::endsWith($file, '.php')) {
                $modelClass = 'App\\Models\\Tenant\\' . pathinfo($file, PATHINFO_FILENAME);

                if (class_exists($modelClass)) {
                    $model = new $modelClass;
                    $tableName = $model->getTable();

                    // تحقق إذا الموديل فيه SoftDeletes
                    if (in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', class_uses($model))) {
                        if(Schema::hasColumn($tableName, 'deleted_at')) {
                            continue; // تخطى إذا العمود موجود بالفعل
                        }
                        Schema::table($tableName, function (Blueprint $table) {
                            $table->softDeletes();
                        });

                    }
                }
            }
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('every', function (Blueprint $table) {
            //
        });
    }
};
