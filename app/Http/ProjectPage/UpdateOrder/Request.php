<?php

namespace App\Http\ProjectPage\UpdateOrder;

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
            'pages' => 'required|array',
            'pages.*.id' => 'required|integer',
            'pages.*.sort_order' => 'required|integer'
        ];
    }
}