<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'key' => 'nullable|string',
            'sources' => 'nullable|string',
            'domains' => 'nullable|string',
            'exclude_domains' => 'nullable|string',
            'from' => 'nullable|string',
            'to' => 'nullable|string',
            'sort_by' => 'nullable|string',
            'page_size' => 'nullable|string',
            'page' => 'nullable|string',
            'category' => 'nullable|string',
            'country' => 'nullable|string',
        ];
    }
}
