<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicPermissionRule extends Model
{
    protected $fillable = [
        'resource_type',
        'action',
        'required_permissions',
        'required_roles',
        'minimum_role_level',
        'custom_logic',
        'description',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'required_permissions' => 'array',
        'required_roles' => 'array',
        'is_active' => 'boolean',
        'custom_logic' => 'array',
        'priority' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForResource($query, $resourceType)
    {
        return $query->where('resource_type', $resourceType);
    }

    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // 권한 체크 도우미 메소드
    public function checkPermission($user, $context = [])
    {
        // 게스트 사용자 처리
        if (!$user) {
            return $this->checkGuestPermission($context);
        }

        // 필수 권한 체크
        if ($this->required_permissions) {
            if (!$user->hasAllPermissions($this->required_permissions)) {
                return false;
            }
        }

        // 필수 역할 체크
        if ($this->required_roles) {
            if (!$user->hasAnyRole($this->required_roles)) {
                return false;
            }
        }

        // 최소 역할 레벨 체크 (기존 시스템과의 호환성)
        if ($this->minimum_role_level) {
            // 사용자의 최고 역할 레벨 계산
            $userMaxLevel = $this->getUserMaxRoleLevel($user);
            if ($userMaxLevel < $this->minimum_role_level) {
                return false;
            }
        }

        // 커스텀 로직 실행 (보안 주의)
        if ($this->custom_logic) {
            try {
                // 간단한 조건문만 허용 (보안상 eval 대신 제한적 파싱)
                return $this->evaluateCustomLogic($user, $context);
            } catch (\Exception $e) {
                // 로그 기록 후 false 반환
                \Log::warning('Custom logic execution failed', [
                    'rule_id' => $this->id,
                    'error' => $e->getMessage()
                ]);
                return false;
            }
        }

        return true;
    }

    /**
     * 게스트 사용자 권한 체크
     */
    private function checkGuestPermission($context = [])
    {
        // 커스텀 로직에서 게스트 허용 여부 확인
        if ($this->custom_logic) {
            try {
                $logic = json_decode($this->custom_logic, true);
                if (isset($logic['allow_guest']) && $logic['allow_guest']) {
                    // 공개 상태 확인 등의 추가 로직 실행
                    return $this->evaluateCustomLogic(null, $context);
                }
            } catch (\Exception $e) {
                \Log::warning('Guest permission check failed', [
                    'rule_id' => $this->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return false;
    }

    private function getUserMaxRoleLevel($user)
    {
        // role_name 기반 권한 체크
        $organizationMember = $user->organizationMemberships()
            ->where('organization_id', request()->route('organization'))
            ->first();
            
        if (!$organizationMember) {
            return 0;
        }
        
        // role_name을 기반으로 한 레벨 계산
        return match($organizationMember->role_name) {
            'user' => 100,
            'service_manager' => 200,
            'organization_admin' => 300,
            'organization_owner' => 400,
            'platform_admin' => 500,
            default => 0
        };
    }

    private function evaluateCustomLogic($user, $context)
    {
        // 안전한 커스텀 로직 실행
        // 여기서는 간단한 JSON 기반 조건문만 허용
        $logic = json_decode($this->custom_logic, true);
        
        if (!$logic) {
            return false;
        }

        // 게스트 사용자의 경우 특별 처리
        if (!$user) {
            // allow_guest가 true인 경우에만 게스트 접근 허용
            if (!isset($logic['allow_guest']) || !$logic['allow_guest']) {
                return false;
            }

            // 추가 조건들 확인
            foreach ($logic as $key => $value) {
                if ($key === 'allow_guest') continue;
                
                $condition = ['type' => $key, 'value' => $value];
                if (!$this->evaluateGuestCondition($condition, $context)) {
                    return false;
                }
            }
            
            return true;
        }

        // 로그인 사용자 - 기존 로직
        // 간단한 AND/OR 조건 처리 예시
        if (isset($logic['and'])) {
            foreach ($logic['and'] as $condition) {
                if (!$this->evaluateCondition($user, $condition, $context)) {
                    return false;
                }
            }
            return true;
        }

        if (isset($logic['or'])) {
            foreach ($logic['or'] as $condition) {
                if ($this->evaluateCondition($user, $condition, $context)) {
                    return true;
                }
            }
            return false;
        }

        return $this->evaluateCondition($user, $logic, $context);
    }

    private function evaluateCondition($user, $condition, $context)
    {
        // 게스트 사용자에 대한 특별 조건들 처리
        if (!$user) {
            return $this->evaluateGuestCondition($condition, $context);
        }

        // 로그인 사용자 조건들 처리
        switch ($condition['type'] ?? '') {
            case 'user_id':
                return $user->id == ($condition['value'] ?? null);
            case 'organization_owner':
                return $user->organizations()->where('organization_id', $context['organization_id'] ?? 0)
                    ->wherePivot('role_name', 'organization_owner')->exists();
            case 'context_match':
                return ($context[$condition['key']] ?? null) == ($condition['value'] ?? null);
            default:
                return false;
        }
    }

    /**
     * 게스트 사용자에 대한 조건 평가
     */
    private function evaluateGuestCondition($condition, $context)
    {
        switch ($condition['type'] ?? '') {
            case 'check_public_status':
                // 공개 상태 확인
                return $context['is_public'] ?? false;
            case 'check_form_status':
                // 폼 공개 상태 확인
                return $context['form_public'] ?? false;
            case 'check_analytics_public':
                // 분석 데이터 공개 상태 확인
                return $context['analytics_public'] ?? false;
            case 'context_match':
                return ($context[$condition['key']] ?? null) == ($condition['value'] ?? null);
            case 'always_allow':
                return true;
            default:
                return false;
        }
    }
}
