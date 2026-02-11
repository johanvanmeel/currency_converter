<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;

/**
 * Fetches exchange rates from FloatRates.
 */
class FetchExchangeRates extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-exchange-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch exchange rates from FloatRates';

    /**
     * Execute the console command.
     */
    public function handle(): void {
        $this->info('Fetching list of available currencies...');

        $usd = Currency::firstOrCreate(
            ['code' => strtoupper('USD')],
            ['name' => strtoupper('U.S. Dollar')]
        );

        $this->fetchForCurrency($usd->code);

        $currencies = Currency::where('code', '!=', $usd->code)->get();
        $this->info("Found " . $currencies->count() . " currencies. Fetching rates for each...");

        $bar = $this->output->createProgressBar($currencies->count());
        $bar->start();

        foreach ($currencies as $currency) {
            $this->fetchForCurrency(strtolower($currency->code));
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Exchange rates fetched successfully.');
    }

    /**
     * Retrieves the exchange rates for the given currency code.
     *
     * @param $code
     *   The currency code to fetch rates for.
     *
     * @return void
     */
    private function fetchForCurrency($code): void {
        $url = "https://www.floatrates.com/daily/{$code}.json";
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();

            $fromCurrency = Currency::firstOrCreate(
                ['code' => strtoupper($code)],
                ['name' => strtoupper($code)]
            );

            foreach ($data as $currencyData) {
                $toCurrency = Currency::firstOrCreate(
                    ['code' => strtoupper($currencyData['code'])],
                    ['name' => $currencyData['name']]
                );

                ExchangeRate::updateOrCreate(
                    [
                        'from_currency_id' => $fromCurrency->id,
                        'to_currency_id' => $toCurrency->id,
                    ],
                    [
                        'rate' => $currencyData['rate'],
                    ]
                );
            }
        }
        else {
            $this->error("Failed to fetch rates for {$code}");
        }
    }

}
