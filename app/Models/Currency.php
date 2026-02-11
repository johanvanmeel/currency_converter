<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The model for the currencies.
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int,
 *     \App\Models\ExchangeRate> $exchangeRates
 */
class Currency extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Get the exchange rates for this currency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ExchangeRate, \App\Models\Currency>
     */
    public function exchangeRates(): HasMany {
        return $this->hasMany(ExchangeRate::class, 'from_currency_id');
    }

}
