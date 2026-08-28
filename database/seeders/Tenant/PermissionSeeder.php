<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = defaultPermissionsList();

        $permissionsData = [];

        foreach ($permissions as $key => $value) {
            foreach($value as $permission){
                $data = ['name' => $key.'.'.$permission, 'guard_name' => 'tenant_admin'];
                $permissionsData[] = $data;
            }
        }

        $existingNames = Permission::where('guard_name', 'tenant_admin')->pluck('name')->all();

        $newPermissionsData = array_values(array_filter(
            $permissionsData,
            fn ($data) => !in_array($data['name'], $existingNames)
        ));

        foreach (array_chunk($newPermissionsData, 100) as $chunk) {
            Permission::insert(array_map(fn ($data) => [
                ...$data,
                'created_at' => now(),
                'updated_at' => now(),
            ], $chunk));
        }

        $maxId = Permission::max('id') ?? 0;
        DB::statement('ALTER TABLE permissions AUTO_INCREMENT = ' . ($maxId + 1));

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
