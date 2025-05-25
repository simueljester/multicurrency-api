<?php

namespace App\Enums;

enum ExchangeRateSourceEnum: string
{
    case Manual = 'Manual';
    case OpenExchangeRates = 'OpenExchangeRates';

    public function label(): string
    {
        return match ($this) {
            self::Manual => 'Manual',
            self::OpenExchangeRates => 'Open Exchange Rates',
        };
    }
}
