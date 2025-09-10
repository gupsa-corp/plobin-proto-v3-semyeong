<?php

namespace App\Http\Controllers\Permissions\UpdateRolePermissions;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'role_name' => 'required|string|exists:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name'
        ];
    }

    public function messages(): array
    {
        return [
            'role_name.required' => 'Role name is required',
            'role_name.exists' => 'Role does not exist',
            'permissions.required' => 'Permissions array is required'
        ];
    }
}
