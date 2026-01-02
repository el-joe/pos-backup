<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;

class ConvertCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:convert-currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currencies = Currency::all()->map(function($currency) {
            if(in_array($currency->code,['USD','KID'])){
                $currency->conversion_rate = 1;
            }else{
                $convertedAmount = CurrencyConverter::convert(1)
                            ->from('USD')
                            ->to($currency->code)
                            ->get();
                $currency->conversion_rate = $convertedAmount;
            }

            $currency->save();
        });
    }
}
