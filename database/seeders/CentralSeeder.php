<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CentralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = $this->loadCountries();
        $currencies = $this->loadCurrencies();

        // Seed countries
        if (!empty($countries)) {
            foreach ($countries as $c) {
                DB::table('countries')->updateOrInsert(
                    ['code' => $c['code']],
                    [
                        'name' => $c['name'],
                    ]
                );
            }
        }

        // Seed currencies
        if (!empty($currencies)) {
            foreach ($currencies as $cur) {
                DB::table('currencies')->updateOrInsert(
                    ['code' => $cur['code']],
                    [
                        'name' => $cur['name'],
                        'symbol' => $cur['symbol'] ?? null,
                        'country_code' => $cur['country_code'] ?? null,
                        'conversion_rate' => 1,
                    ]
                );
            }
        }
    }

    private function loadCountries(): array
    {
        $path = database_path('seeders/data/countries.json');
        if (File::exists($path)) {
            $json = File::get($path);
            $data = json_decode($json, true);
            if (is_array($data)) return $data;
        }
        // Fallback minimal list
        return [
            ['name'=>'United States','code'=>'US','currency_code'=>'USD','currency_symbol'=>'$'],
            ['name'=>'Canada','code'=>'CA','currency_code'=>'CAD','currency_symbol'=>'$'],
            ['name'=>'United Kingdom','code'=>'GB','currency_code'=>'GBP','currency_symbol'=>'£'],
        ];
    }

    private function loadCurrencies(): array
    {
        $path = database_path('seeders/data/currencies.json');
        if (File::exists($path)) {
            $json = File::get($path);
            $data = json_decode($json, true);
            if (is_array($data)) return $data;
        }
        // Fallback minimal list
        return [
            ['code'=>'USD','name'=>'US Dollar','symbol'=>'$'],
            ['code'=>'CAD','name'=>'Canadian Dollar','symbol'=>'$'],
            ['code'=>'GBP','name'=>'British Pound','symbol'=>'£'],
        ];
    }
}
