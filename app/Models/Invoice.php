<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * The attributes that are fillables.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'title',
        'amount',
        'currency_code',
        'base_currency',
        'exchange_rate_id',
        'exchange_rate',
        'exchange_rate_timestamp',
        'amount_in_base_currency',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'exchange_rate_timestamp' => 'datetime',
        'amount' => 'float:2',
        'exchange_rate' => 'float:6',
        'amount_in_base_currency' => 'float:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function exchangeRate()
    {
        return $this->belongsTo(ExchangeRate::class);
    }
}
