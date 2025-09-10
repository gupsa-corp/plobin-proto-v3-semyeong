<?php

namespace App\Http\Controllers\ProjectPage\Store;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'config' => 'nullable|array',
            'parent_id' => 'nullable|integer|exists:project_pages,id'
        ];
    }
}
