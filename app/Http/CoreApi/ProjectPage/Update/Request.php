<?php

namespace App\Http\CoreApi\ProjectPage\Update;

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
            'name' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'custom_screen_settings' => 'nullable|array',
            'custom_screen_settings.screen_name' => 'nullable|string|max:255',
            'custom_screen_settings.screen_title' => 'nullable|string|max:255'
        ];
    }
}
