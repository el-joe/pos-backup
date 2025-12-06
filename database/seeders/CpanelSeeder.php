<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CpanelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createCpanelAdmin();
    }

    private function createCpanelAdmin()
    {
        return Admin::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'CPanel Admin',
                'password' => '123456'
            ]
        );
    }
}
