<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

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

        Permission::upsert($permissionsData, ['name', 'guard_name']);

    }
}
