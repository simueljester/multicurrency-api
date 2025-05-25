<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetInvoiceRequest extends FormRequest
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
            'keyword' => ['nullable', 'string'],
            'per_page' => ['nullable', 'numeric'],
            'page' => ['nullable', 'numeric'],
            'include' => ['nullable', 'string'],
            'sort_by' => [
                'nullable',
                'string',
                'required_with:sort_type',
                'in:title,created_at',
            ],
            'sort_type' => [
                'nullable',
                'string',
                'required_with:sort_by',
                'in:asc,desc',
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
            'sort_by.in' => 'The sort_by must be either title or created_at.',
            'sort_type.required_with' => 'The sort_type field is required when sort_by is present.',
            'sort_type.in' => 'The sort_type must be either asc or desc.',
        ];
    }
}
