<?php

namespace App\Rules;

use App\Models\Currency;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;

class ValidCurrency implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validCurrencies = Cache::remember('valid_currencies', now()->addDay(), function () {
            return Currency::pluck('code')->map(fn ($code) => strtoupper($code))->toArray();
        });

        if (! in_array(strtoupper($value), $validCurrencies)) {
            $fail("The selected {$attribute} is not a valid or supported currency.");
        }
    }
}
