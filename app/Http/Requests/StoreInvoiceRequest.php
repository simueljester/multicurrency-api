<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'company_id' => ['required', 'exists:companies,id'],
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Custom validation message for each error
     */
    public function messages(): array
    {
        return [
            'company_id.required' => 'Please provide a company ID.',
            'company_id.exists' => 'The selected company does not exist.',
            'title.required' => 'An invoice title is required.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
        ];
    }
}
