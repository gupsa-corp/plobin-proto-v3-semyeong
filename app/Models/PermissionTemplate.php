<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionTemplate extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions_config',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'permissions_config' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('display_name');
    }

    // 템플릿 적용 메소드
    public function applyToUser($user)
    {
        $config = $this->permissions_config;

        // 역할 할당
        if (isset($config['roles'])) {
            $user->syncRoles($config['roles']);
        }

        // 직접 권한 할당
        if (isset($config['permissions'])) {
            $user->syncPermissions($config['permissions']);
        }

        return true;
    }

    // 템플릿 검증
    public function validateConfig()
    {
        $config = $this->permissions_config;
        $errors = [];

        // 역할 유효성 검증
        if (isset($config['roles'])) {
            foreach ($config['roles'] as $role) {
                if (!\Spatie\Permission\Models\Role::where('name', $role)->exists()) {
                    $errors[] = "Role '{$role}' does not exist";
                }
            }
        }

        // 권한 유효성 검증
        if (isset($config['permissions'])) {
            foreach ($config['permissions'] as $permission) {
                if (!\Spatie\Permission\Models\Permission::where('name', $permission)->exists()) {
                    $errors[] = "Permission '{$permission}' does not exist";
                }
            }
        }

        return empty($errors) ? true : $errors;
    }
}
