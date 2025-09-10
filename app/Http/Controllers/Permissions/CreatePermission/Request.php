<?php

namespace App\Http\Controllers\Permissions\CreatePermission;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Permission name is required',
            'name.unique' => 'Permission name already exists',
            'category.required' => 'Category is required'
        ];
    }
}
