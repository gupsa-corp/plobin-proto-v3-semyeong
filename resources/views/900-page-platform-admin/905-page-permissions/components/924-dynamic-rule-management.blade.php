{{-- 동적 규칙 관리 컴포넌트 --}}
<div class="dynamic-rule-management" x-data="dynamicRuleManagement">
    
    {{-- 액션 버튼들 --}}
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <button @click="showCreateModal = true" 
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                새 동적 규칙 추가
            </button>
            <button @click="testAllRules" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                전체 규칙 테스트
            </button>
        </div>
        
        <div class="flex items-center gap-2">
            <select x-model="statusFilter" 
                    @change="filterRules"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">전체 상태</option>
                <option value="active">활성</option>
                <option value="inactive">비활성</option>
            </select>
            <input type="text" 
                   x-model="searchTerm"
                   @input="filterRules"
                   placeholder="규칙명으로 검색..."
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    {{-- 동적 규칙 목록 테이블 --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">동적 권한 규칙 목록</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            규칙명
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            리소스/액션
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            필수 조건
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            우선순위
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            상태
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            액션
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="rule in filteredRules" :key="rule.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900" x-text="rule.name"></div>
                                    <div class="text-sm text-gray-500" x-text="rule.description"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span x-text="rule.resource_type"></span> / <span x-text="rule.action"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <template x-if="rule.required_permissions.length > 0">
                                        <div>
                                            <div x-text="rule.required_permissions.length + '개 권한'"></div>
                                            <div x-text="rule.required_roles.length + '개 역할'"></div>
                                        </div>
                                    </template>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full"
                                      :class="{
                                          'bg-red-100 text-red-800': rule.priority >= 80,
                                          'bg-yellow-100 text-yellow-800': rule.priority >= 50 && rule.priority < 80,
                                          'bg-blue-100 text-blue-800': rule.priority < 50
                                      }"
                                      x-text="rule.priority"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           :checked="rule.is_active"
                                           @change="toggleRuleStatus(rule.id)"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700" 
                                          x-text="rule.is_active ? '활성' : '비활성'"></span>
                                </label>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="testRule(rule.id)" 
                                            class="text-green-600 hover:text-green-900">테스트</button>
                                    <button @click="editRule(rule.id)" 
                                            class="text-blue-600 hover:text-blue-900">편집</button>
                                    <button @click="deleteRule(rule.id)" 
                                            class="text-red-600 hover:text-red-900">삭제</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- 규칙 생성/편집 모달 --}}
    <div x-show="showCreateModal || showEditModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-96 overflow-y-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4" 
                x-text="showCreateModal ? '새 동적 규칙 추가' : '동적 규칙 편집'"></h3>
            
            <form @submit.prevent="saveRule">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">규칙명</label>
                        <input type="text" 
                               x-model="ruleForm.name"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">우선순위 (0-100)</label>
                        <input type="number" 
                               x-model="ruleForm.priority"
                               min="0" max="100"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">리소스 타입</label>
                        <select x-model="ruleForm.resource_type"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">선택하세요</option>
                            <option value="member_management">멤버 관리</option>
                            <option value="project_management">프로젝트 관리</option>
                            <option value="billing_management">결제 관리</option>
                            <option value="organization_settings">조직 설정</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">액션</label>
                        <input type="text" 
                               x-model="ruleForm.action"
                               placeholder="예: super_create, special_access"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">설명</label>
                    <textarea x-model="ruleForm.description"
                              rows="3"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">필수 권한 (쉼표로 구분)</label>
                    <input type="text" 
                           x-model="ruleForm.required_permissions_text"
                           placeholder="예: create projects, manage system settings"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">필수 역할 (쉼표로 구분)</label>
                    <input type="text" 
                           x-model="ruleForm.required_roles_text"
                           placeholder="예: 플랫폼 관리자, 조직 관리자"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">커스텀 로직 (JSON)</label>
                    <textarea x-model="ruleForm.custom_logic"
                              rows="4"
                              placeholder='{"conditions": [{"type": "AND", "rules": []}]}'
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"></textarea>
                </div>

                <div class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" 
                               x-model="ruleForm.is_active"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">활성화</span>
                    </label>
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" 
                            @click="closeModal"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        취소
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                        <span x-text="showCreateModal ? '추가' : '저장'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 테스트 결과 모달 --}}
    <div x-show="showTestModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">규칙 테스트 결과</h3>
            
            <div class="space-y-3">
                <div class="p-3 rounded-lg" 
                     :class="testResult.success ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" 
                             :class="testResult.success ? 'text-green-600' : 'text-red-600'" 
                             fill="currentColor" viewBox="0 0 20 20">
                            <path x-show="testResult.success" 
                                  fill-rule="evenodd" 
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" 
                                  clip-rule="evenodd"/>
                            <path x-show="!testResult.success" 
                                  fill-rule="evenodd" 
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" 
                                  clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium" 
                              :class="testResult.success ? 'text-green-800' : 'text-red-800'"
                              x-text="testResult.success ? '테스트 성공' : '테스트 실패'"></span>
                    </div>
                    <p class="mt-1 text-sm" 
                       :class="testResult.success ? 'text-green-700' : 'text-red-700'"
                       x-text="testResult.message"></p>
                </div>
                
                <div x-show="testResult.details">
                    <h4 class="font-medium text-gray-900">세부 정보:</h4>
                    <pre class="mt-2 text-sm bg-gray-100 p-3 rounded overflow-x-auto" x-text="testResult.details"></pre>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button @click="showTestModal = false" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                    닫기
                </button>
            </div>
        </div>
    </div>
    
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dynamicRuleManagement', () => ({
        searchTerm: '',
        statusFilter: '',
        rules: [
            {
                id: 1,
                name: '특별 프로젝트 생성',
                description: '높은 권한이 필요한 특별 프로젝트 생성 규칙',
                resource_type: 'project_management',
                action: 'super_create',
                required_permissions: ['create projects', 'manage system settings'],
                required_roles: ['플랫폼 관리자'],
                custom_logic: '{"type": "AND", "conditions": []}',
                priority: 90,
                is_active: true,
                created_at: '2024-01-15'
            },
            {
                id: 2,
                name: '결제 정보 특별 접근',
                description: '민감한 결제 정보 접근을 위한 특별 규칙',
                resource_type: 'billing_management',
                action: 'sensitive_access',
                required_permissions: ['view billing', 'manage payment methods'],
                required_roles: ['플랫폼 관리자', '재무 담당자'],
                custom_logic: '{"type": "OR", "conditions": []}',
                priority: 80,
                is_active: true,
                created_at: '2024-02-01'
            },
            {
                id: 3,
                name: '조직 삭제 권한',
                description: '조직 완전 삭제를 위한 고위험 규칙',
                resource_type: 'organization_settings',
                action: 'delete_organization',
                required_permissions: ['manage organizations', 'super admin'],
                required_roles: ['플랫폼 관리자'],
                custom_logic: '{"type": "AND", "require_mfa": true}',
                priority: 100,
                is_active: false,
                created_at: '2024-02-15'
            }
        ],
        filteredRules: [],
        showCreateModal: false,
        showEditModal: false,
        showTestModal: false,
        editingRuleId: null,
        ruleForm: {
            name: '',
            description: '',
            resource_type: '',
            action: '',
            required_permissions_text: '',
            required_roles_text: '',
            custom_logic: '',
            priority: 50,
            is_active: true
        },
        testResult: {
            success: false,
            message: '',
            details: ''
        },

        init() {
            this.filteredRules = this.rules;
            console.log('Dynamic rule management initialized');
        },

        filterRules() {
            this.filteredRules = this.rules.filter(rule => {
                const matchesSearch = !this.searchTerm || 
                    rule.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                    rule.description.toLowerCase().includes(this.searchTerm.toLowerCase());
                
                const matchesStatus = !this.statusFilter || 
                    (this.statusFilter === 'active' && rule.is_active) ||
                    (this.statusFilter === 'inactive' && !rule.is_active);
                
                return matchesSearch && matchesStatus;
            });
        },

        toggleRuleStatus(ruleId) {
            const rule = this.rules.find(r => r.id === ruleId);
            if (rule) {
                rule.is_active = !rule.is_active;
                this.filterRules();
                console.log(`Rule ${ruleId} status changed to:`, rule.is_active);
            }
        },

        testRule(ruleId) {
            const rule = this.rules.find(r => r.id === ruleId);
            if (rule) {
                // 시뮬레이션된 테스트 결과
                this.testResult = {
                    success: Math.random() > 0.3,
                    message: this.testResult.success 
                        ? `규칙 "${rule.name}"이 정상적으로 작동합니다.`
                        : `규칙 "${rule.name}"에서 오류가 발견되었습니다.`,
                    details: JSON.stringify({
                        rule_id: ruleId,
                        resource_type: rule.resource_type,
                        action: rule.action,
                        test_time: new Date().toISOString(),
                        validation_result: this.testResult.success ? 'PASS' : 'FAIL'
                    }, null, 2)
                };
                this.showTestModal = true;
            }
        },

        testAllRules() {
            console.log('Testing all active rules...');
            const activeRules = this.rules.filter(r => r.is_active);
            // 실제 구현시 전체 규칙 테스트 로직
            alert(`${activeRules.length}개의 활성 규칙을 테스트합니다.`);
        },

        editRule(ruleId) {
            const rule = this.rules.find(r => r.id === ruleId);
            if (rule) {
                this.editingRuleId = ruleId;
                this.ruleForm = {
                    name: rule.name,
                    description: rule.description,
                    resource_type: rule.resource_type,
                    action: rule.action,
                    required_permissions_text: rule.required_permissions.join(', '),
                    required_roles_text: rule.required_roles.join(', '),
                    custom_logic: rule.custom_logic,
                    priority: rule.priority,
                    is_active: rule.is_active
                };
                this.showEditModal = true;
            }
        },

        deleteRule(ruleId) {
            if (confirm('정말로 이 동적 규칙을 삭제하시겠습니까?')) {
                console.log('Deleting rule:', ruleId);
                // 실제 구현시 API 호출
            }
        },

        saveRule() {
            if (this.showCreateModal) {
                console.log('Creating new rule:', this.ruleForm);
            } else {
                console.log('Updating rule:', this.editingRuleId, this.ruleForm);
            }
            this.closeModal();
        },

        closeModal() {
            this.showCreateModal = false;
            this.showEditModal = false;
            this.editingRuleId = null;
            this.ruleForm = {
                name: '',
                description: '',
                resource_type: '',
                action: '',
                required_permissions_text: '',
                required_roles_text: '',
                custom_logic: '',
                priority: 50,
                is_active: true
            };
        }
    }));
});
</script>