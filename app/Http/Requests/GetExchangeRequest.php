<?php

namespace App\Http\Requests;

use App\Rules\ValidCurrency;
use Illuminate\Foundation\Http\FormRequest;

class GetExchangeRequest extends FormRequest
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
            'per_page' => ['nullable', 'numeric'],
            'page' => ['nullable', 'numeric'],
            'sort_by' => [
                'nullable',
                'string',
                'required_with:sort_type',
                'in:fetched_at',
            ],
            'sort_type' => [
                'nullable',
                'string',
                'required_with:sort_by',
                'in:asc,desc',
            ],
            'from_currency' => [
                'nullable',
                'string',
                'required_with:to_currency',
                new ValidCurrency,
            ],
            'to_currency' => [
                'nullable',
                'string',
                'required_with:from_currency',
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
            'sort_by.required_with' => 'The sort_by field is required when sort_type is present.',
            'sort_by.in' => 'The sort_by must be fetched_at only.',
            'sort_type.required_with' => 'The sort_type field is required when sort_by is present.',
            'sort_type.in' => 'The sort_type must be either asc or desc.',
            'from_currency.required_with' => 'The from_currency field is required when to_currency is present.',
            'to_currency.required_with' => 'The to_currency field is required when from_currency is present.',
        ];
    }
}
