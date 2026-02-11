<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurrencyConverterController extends Controller {

    /**
     * Displays the currency converter index page.
     *
     * @return View
     */
    public function index(): View {
        $currencies = Currency::orderBy('code')->get();

        return view('currency.index', compact('currencies'));
    }

    /**
     * Performs the currency conversion and display results.
     *
     * @param Request $request
     *   The request object.
     *
     * @return View
     */
    public function convert(Request $request): View {
        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $fromCurrency = Currency::findOrFail($request->currency_id);
        $amount = $request->amount;

        $conversions = ExchangeRate::where('from_currency_id', $fromCurrency->id)
            ->join('currencies', 'exchange_rates.to_currency_id', '=', 'currencies.id')
            ->orderBy('currencies.code')
            ->select('exchange_rates.*')
            ->with('toCurrency')
            ->get()
            ->map(function ($exchangeRate) use ($amount) {
                return [
                    'code' => $exchangeRate->toCurrency->code,
                    'name' => $exchangeRate->toCurrency->name,
                    'rate' => $exchangeRate->rate,
                    'converted_amount' => $amount * $exchangeRate->rate,
                ];
            });

        $currencies = Currency::orderBy('code')->get();

        return view('currency.index', compact('currencies', 'conversions', 'fromCurrency', 'amount'));
    }

}
