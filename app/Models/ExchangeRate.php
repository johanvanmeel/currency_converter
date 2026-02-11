<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The model for the exchange rates.
 *
 * @property int $id
 * @property int $from_currency_id
 * @property int $to_currency_id
 * @property float $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Currency $fromCurrency
 * @property-read \App\Models\Currency $toCurrency
 */
class ExchangeRate extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'from_currency_id',
        'to_currency_id',
        'rate',
    ];

    /**
     * Returns the currency from which the rate is converted.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Currency, \App\Models\ExchangeRate>
     */
    public function fromCurrency(): BelongsTo {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    /**
     * Returns the currency to which the rate is converted.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Currency, \App\Models\ExchangeRate>
     */
    public function toCurrency(): BelongsTo {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'rate' => 'float',
        ];
    }

}
