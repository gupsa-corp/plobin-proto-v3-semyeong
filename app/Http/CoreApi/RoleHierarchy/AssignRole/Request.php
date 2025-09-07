<?php

namespace App\Http\CoreApi\RoleHierarchy\AssignRole;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'target_user_id' => 'required|exists:users,id',
            'role_name' => 'required|string|exists:roles,name'
        ];
    }

    public function messages(): array
    {
        return [
            'target_user_id.required' => 'Target user ID is required',
            'target_user_id.exists' => 'User does not exist',
            'role_name.required' => 'Role name is required',
            'role_name.exists' => 'Role does not exist'
        ];
    }
}