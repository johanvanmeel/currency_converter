<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyConversionTest extends TestCase
{
    use RefreshDatabase;

    public function test_currency_index_page_loads()
    {
        $response = $this->get(route('currency.index'));

        $response->assertStatus(200);
        $response->assertSee('Currency Converter');
    }

    public function test_currency_conversion_works()
    {
        $usd = Currency::create(['code' => 'USD', 'name' => 'US Dollar']);
        $eur = Currency::create(['code' => 'EUR', 'name' => 'Euro']);

        ExchangeRate::create([
            'from_currency_id' => $usd->id,
            'to_currency_id' => $eur->id,
            'rate' => 0.92,
        ]);

        $response = $this->get(route('currency.convert', [
            'currency_id' => $usd->id,
            'amount' => 100,
        ]));

        $response->assertStatus(200);
        $response->assertSee('92.00 EUR');
    }
}
