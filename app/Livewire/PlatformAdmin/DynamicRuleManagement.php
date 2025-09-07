<?php

namespace App\Livewire\PlatformAdmin;

use Livewire\Component;
use App\Models\DynamicPermissionRule;
use App\Services\DynamicPermissionService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DynamicRuleManagement extends Component
{
    public $rules = [];
    public $selectedRule = null;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showTestModal = false;
    
    // 폼 필드
    public $resourceType = '';
    public $action = '';
    public $description = '';
    public $requiredPermissions = [];
    public $requiredRoles = [];
    public $customLogic = '';
    public $isActive = true;
    public $priority = 0;
    
    // 테스트 필드
    public $testUserId = null;
    public $testContext = '';
    public $testResult = null;
    
    // 편집 중인 규칙
    public $editingRule = null;

    protected $rules_validation = [
        'resourceType' => 'required|string|max:255',
        'action' => 'required|string|max:255',
        'description' => 'nullable|string',
        'requiredPermissions' => 'array',
        'requiredRoles' => 'array',
        'customLogic' => 'nullable|json',
        'isActive' => 'boolean',
        'priority' => 'integer|min:0|max:999'
    ];

    public function mount()
    {
        $this->loadRules();
    }

    public function loadRules()
    {
        $this->rules = DynamicPermissionRule::orderBy('priority', 'desc')
            ->orderBy('resource_type')
            ->orderBy('action')
            ->get()
            ->map(function ($rule) {
                return [
                    'id' => $rule->id,
                    'resource_type' => $rule->resource_type,
                    'action' => $rule->action,
                    'description' => $rule->description,
                    'is_active' => $rule->is_active,
                    'priority' => $rule->priority,
                    'required_permissions' => $rule->required_permissions ?? [],
                    'required_roles' => $rule->required_roles ?? [],
                    'custom_logic' => $rule->custom_logic,
                    'created_at' => $rule->created_at,
                    'updated_at' => $rule->updated_at,
                ];
            })
            ->groupBy('resource_type')
            ->toArray();
    }

    public function selectRule($ruleId)
    {
        $rule = DynamicPermissionRule::find($ruleId);
        if ($rule) {
            $this->selectedRule = [
                'id' => $rule->id,
                'resource_type' => $rule->resource_type,
                'action' => $rule->action,
                'description' => $rule->description,
                'is_active' => $rule->is_active,
                'priority' => $rule->priority,
                'required_permissions' => $rule->required_permissions ?? [],
                'required_roles' => $rule->required_roles ?? [],
                'custom_logic' => $rule->custom_logic,
                'created_at' => $rule->created_at,
                'updated_at' => $rule->updated_at,
            ];
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($ruleId)
    {
        $rule = DynamicPermissionRule::find($ruleId);
        if ($rule) {
            $this->editingRule = $rule;
            $this->resourceType = $rule->resource_type;
            $this->action = $rule->action;
            $this->description = $rule->description;
            $this->requiredPermissions = $rule->required_permissions ?? [];
            $this->requiredRoles = $rule->required_roles ?? [];
            $this->customLogic = $rule->custom_logic ? json_encode($rule->custom_logic, JSON_PRETTY_PRINT) : '';
            $this->isActive = $rule->is_active;
            $this->priority = $rule->priority;
            $this->showEditModal = true;
        }
    }

    public function openDeleteModal($ruleId)
    {
        $this->editingRule = DynamicPermissionRule::find($ruleId);
        $this->showDeleteModal = true;
    }

    public function openTestModal($ruleId)
    {
        $this->selectRule($ruleId);
        $this->testResult = null;
        $this->testUserId = null;
        $this->testContext = '';
        $this->showTestModal = true;
    }

    public function createRule()
    {
        $this->validate($this->rules_validation);

        try {
            $customLogicData = null;
            if (!empty($this->customLogic)) {
                $customLogicData = json_decode($this->customLogic, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('커스텀 로직의 JSON 형식이 올바르지 않습니다.');
                }
            }

            DynamicPermissionRule::create([
                'resource_type' => $this->resourceType,
                'action' => $this->action,
                'description' => $this->description,
                'required_permissions' => !empty($this->requiredPermissions) ? $this->requiredPermissions : null,
                'required_roles' => !empty($this->requiredRoles) ? $this->requiredRoles : null,
                'custom_logic' => $customLogicData,
                'is_active' => $this->isActive,
                'priority' => $this->priority
            ]);

            // 캐시 클리어
            app(DynamicPermissionService::class)->clearCache();

            $this->loadRules();
            $this->resetForm();
            $this->showCreateModal = false;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "동적 규칙 '{$this->resourceType}.{$this->action}'이 생성되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '규칙 생성 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function updateRule()
    {
        if (!$this->editingRule) return;

        $this->validate($this->rules_validation);

        try {
            $customLogicData = null;
            if (!empty($this->customLogic)) {
                $customLogicData = json_decode($this->customLogic, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('커스텀 로직의 JSON 형식이 올바르지 않습니다.');
                }
            }

            $this->editingRule->update([
                'resource_type' => $this->resourceType,
                'action' => $this->action,
                'description' => $this->description,
                'required_permissions' => !empty($this->requiredPermissions) ? $this->requiredPermissions : null,
                'required_roles' => !empty($this->requiredRoles) ? $this->requiredRoles : null,
                'custom_logic' => $customLogicData,
                'is_active' => $this->isActive,
                'priority' => $this->priority
            ]);

            // 캐시 클리어
            app(DynamicPermissionService::class)->clearCache();

            $this->loadRules();
            $this->resetForm();
            $this->showEditModal = false;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "동적 규칙 '{$this->resourceType}.{$this->action}'이 업데이트되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '규칙 업데이트 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteRule()
    {
        if (!$this->editingRule) return;

        try {
            $ruleName = $this->editingRule->resource_type . '.' . $this->editingRule->action;
            $this->editingRule->delete();

            // 캐시 클리어
            app(DynamicPermissionService::class)->clearCache();

            $this->loadRules();
            $this->showDeleteModal = false;
            $this->editingRule = null;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "동적 규칙 '{$ruleName}'이 삭제되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '규칙 삭제 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleRuleStatus($ruleId)
    {
        try {
            $rule = DynamicPermissionRule::find($ruleId);
            if ($rule) {
                $rule->update(['is_active' => !$rule->is_active]);
                
                // 캐시 클리어
                app(DynamicPermissionService::class)->clearCache();
                
                $this->loadRules();
                
                $status = $rule->is_active ? '활성화' : '비활성화';
                $this->dispatch('notification', [
                    'type' => 'success',
                    'message' => "규칙이 {$status}되었습니다."
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '규칙 상태 변경 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function testRule()
    {
        if (!$this->selectedRule || !$this->testUserId) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '규칙과 사용자를 선택해주세요.'
            ]);
            return;
        }

        try {
            $user = \App\Models\User::find($this->testUserId);
            if (!$user) {
                $this->dispatch('notification', [
                    'type' => 'error',
                    'message' => '사용자를 찾을 수 없습니다.'
                ]);
                return;
            }

            $context = [];
            if (!empty($this->testContext)) {
                $context = json_decode($this->testContext, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $context = [];
                }
            }

            $result = app(DynamicPermissionService::class)->canPerformAction(
                $user,
                $this->selectedRule['resource_type'],
                $this->selectedRule['action'],
                $context
            );

            $this->testResult = [
                'success' => $result,
                'user' => $user->name,
                'resource_type' => $this->selectedRule['resource_type'],
                'action' => $this->selectedRule['action'],
                'context' => $context,
                'message' => $result ? '권한이 허용되었습니다.' : '권한이 거부되었습니다.'
            ];

        } catch (\Exception $e) {
            $this->testResult = [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => '테스트 중 오류가 발생했습니다: ' . $e->getMessage()
            ];
        }
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showTestModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->resourceType = '';
        $this->action = '';
        $this->description = '';
        $this->requiredPermissions = [];
        $this->requiredRoles = [];
        $this->customLogic = '';
        $this->isActive = true;
        $this->priority = 0;
        $this->editingRule = null;
        $this->resetErrorBag();
    }

    public function getPermissionsProperty()
    {
        return Permission::pluck('name');
    }

    public function getRolesProperty()
    {
        return Role::pluck('name');
    }

    public function getUsersProperty()
    {
        return \App\Models\User::select('id', 'name', 'email')
            ->limit(50)
            ->get();
    }

    public function render()
    {
        return view('900-page-platform-admin.components.924-dynamic-rule-management', [
            'rules' => $this->rules,
            'selectedRule' => $this->selectedRule,
            'permissions' => $this->getPermissionsProperty(),
            'roles' => $this->getRolesProperty(),
            'users' => $this->getUsersProperty()
        ]);
    }
}