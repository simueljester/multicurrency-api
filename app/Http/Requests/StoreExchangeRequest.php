<?php

namespace App\Http\Requests;

use App\Rules\ValidCurrency;
use Illuminate\Foundation\Http\FormRequest;

class StoreExchangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_currency' => ['required', 'string', 'size:3', new ValidCurrency],
            'to_currency' => ['required', 'string', 'size:3', new ValidCurrency],
            'rate' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Custom validation message for each error
     */
    public function messages(): array
    {
        return [
            'from_currency.required' => 'from_currency is required.',
            'from_currency.size' => 'from_currency must be a 3-letter ISO code.',

            'to_currency.required' => 'to_currency is required.',
            'to_currency.size' => 'to_currency must be a 3-letter ISO code.',

            'rate.required' => 'rate is required.',
            'rate.numeric' => 'rate must be numeric.',

        ];
    }
}
