<?php

namespace App\Http\Requests;

use App\Rules\ValidCurrency;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // we can also add authentication validation here. But for now, no need for authentication
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
            'name' => ['required', 'string', 'max:255', 'unique:companies,name'],
            'base_currency' => ['required', 'string', 'size:3', new ValidCurrency], // base currency is automatically parse into uppercase using MUTATOR
        ];
    }

    /**
     * Custom validation message for each error
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Company name is required.',
            'name.unique' => 'A company with this name already exists.',
            'base_currency.required' => 'Base currency is required.',
            'base_currency.size' => 'Base currency must be a 3-letter ISO code.',
        ];
    }
}
