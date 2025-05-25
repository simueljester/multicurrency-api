<?php

namespace App\Http\Requests;

use App\Rules\ValidCurrency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'name')->ignore($this->company),
            ],
            'base_currency' => [
                'sometimes',
                'required',
                'string',
                'size:3',
                new ValidCurrency,
            ],
        ];
    }

    /**
     * Custom validation message for each error
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The company name is required.',
            'name.string' => 'The company name must be a string.',
            'name.max' => 'The company name must not exceed 255 characters.',
            'base_currency.required' => 'The base currency is required.',
            'base_currency.string' => 'The base currency must be a string.',
            'base_currency.size' => 'The base currency must be a 3-letter ISO code.',
        ];
    }
}
