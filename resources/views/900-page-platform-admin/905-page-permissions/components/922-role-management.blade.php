{{-- 역할 관리 컴포넌트 --}}
<div class="role-management" x-data="roleManagement">
    
    {{-- 액션 버튼들 --}}
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <button @click="showCreateModal = true" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                새 역할 추가
            </button>
            <button @click="refreshRoles" 
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                새로고침
            </button>
        </div>
        
        <div class="flex items-center gap-2">
            <input type="text" 
                   x-model="searchTerm"
                   @input="filterRoles"
                   placeholder="역할명으로 검색..."
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    {{-- 역할 목록 테이블 --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">역할 목록</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            역할명
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            권한 수
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            사용자 수
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            생성일
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            액션
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="role in filteredRoles" :key="role.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900" x-text="role.name"></div>
                                    <div class="text-sm text-gray-500" x-text="role.description"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="role.permissions_count + '개 권한'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="role.users_count + '명'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="role.created_at"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editRole(role.id)" 
                                            class="text-blue-600 hover:text-blue-900">편집</button>
                                    <button @click="managePermissions(role.id)" 
                                            class="text-green-600 hover:text-green-900">권한 관리</button>
                                    <button @click="deleteRole(role.id)" 
                                            :disabled="role.users_count > 0"
                                            :class="role.users_count > 0 ? 'text-gray-400 cursor-not-allowed' : 'text-red-600 hover:text-red-900'">
                                        삭제
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- 역할 생성/편집 모달 --}}
    <div x-show="showCreateModal || showEditModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="showCreateModal ? '새 역할 추가' : '역할 편집'"></h3>
            
            <form @submit.prevent="saveRole">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">역할명</label>
                        <input type="text" 
                               x-model="roleForm.name"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">설명</label>
                        <textarea x-model="roleForm.description"
                                  rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" 
                            @click="closeModal"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        취소
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <span x-text="showCreateModal ? '추가' : '저장'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 권한 관리 모달 --}}
    <div x-show="showPermissionModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-96 overflow-y-auto"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            <h3 class="text-lg font-medium text-gray-900 mb-4">권한 관리</h3>
            
            <div class="space-y-4">
                <template x-for="category in permissionCategories" :key="category.name">
                    <div class="border rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3" x-text="category.display_name"></h4>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="permission in category.permissions" :key="permission.id">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           :checked="selectedPermissions.includes(permission.id)"
                                           @change="togglePermission(permission.id)"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700" x-text="permission.display_name"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" 
                        @click="showPermissionModal = false"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    취소
                </button>
                <button @click="savePermissions" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    저장
                </button>
            </div>
        </div>
    </div>
    
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('roleManagement', () => ({
        searchTerm: '',
        roles: [
            {
                id: 1,
                name: '플랫폼 관리자',
                description: '시스템 전체를 관리하는 최고 관리자',
                permissions_count: 24,
                users_count: 2,
                created_at: '2024-01-01'
            },
            {
                id: 2,
                name: '조직 관리자',
                description: '조직 내 모든 권한을 가진 관리자',
                permissions_count: 18,
                users_count: 8,
                created_at: '2024-01-15'
            },
            {
                id: 3,
                name: '프로젝트 관리자',
                description: '프로젝트 관리 권한을 가진 역할',
                permissions_count: 12,
                users_count: 15,
                created_at: '2024-02-01'
            },
            {
                id: 4,
                name: '일반 멤버',
                description: '기본적인 서비스 이용 권한',
                permissions_count: 5,
                users_count: 89,
                created_at: '2024-01-01'
            }
        ],
        filteredRoles: [],
        showCreateModal: false,
        showEditModal: false,
        showPermissionModal: false,
        editingRoleId: null,
        roleForm: {
            name: '',
            description: ''
        },
        selectedPermissions: [],
        permissionCategories: [
            {
                name: 'member_management',
                display_name: '멤버 관리',
                permissions: [
                    { id: 1, display_name: '멤버 초대', name: 'invite members' },
                    { id: 2, display_name: '멤버 제거', name: 'remove members' },
                    { id: 3, display_name: '멤버 역할 수정', name: 'edit member roles' }
                ]
            },
            {
                name: 'project_management',
                display_name: '프로젝트 관리',
                permissions: [
                    { id: 4, display_name: '프로젝트 생성', name: 'create projects' },
                    { id: 5, display_name: '프로젝트 삭제', name: 'delete projects' },
                    { id: 6, display_name: '프로젝트 설정', name: 'manage project settings' }
                ]
            },
            {
                name: 'billing_management',
                display_name: '결제 관리',
                permissions: [
                    { id: 7, display_name: '결제 정보 조회', name: 'view billing' },
                    { id: 8, display_name: '결제 수단 관리', name: 'manage payment methods' },
                    { id: 9, display_name: '플랜 변경', name: 'change subscription' }
                ]
            }
        ],

        init() {
            this.filteredRoles = this.roles;
            console.log('Role management initialized');
        },

        filterRoles() {
            this.filteredRoles = this.roles.filter(role => 
                !this.searchTerm || role.name.toLowerCase().includes(this.searchTerm.toLowerCase())
            );
        },

        refreshRoles() {
            console.log('Refreshing roles...');
            // 실제 구현시 API 호출
        },

        editRole(roleId) {
            const role = this.roles.find(r => r.id === roleId);
            if (role) {
                this.editingRoleId = roleId;
                this.roleForm.name = role.name;
                this.roleForm.description = role.description;
                this.showEditModal = true;
            }
        },

        deleteRole(roleId) {
            if (confirm('정말로 이 역할을 삭제하시겠습니까?')) {
                console.log('Deleting role:', roleId);
                // 실제 구현시 API 호출
            }
        },

        managePermissions(roleId) {
            this.editingRoleId = roleId;
            this.showPermissionModal = true;
            // 실제 구현시 해당 역할의 현재 권한 로드
        },

        saveRole() {
            if (this.showCreateModal) {
                console.log('Creating new role:', this.roleForm);
            } else {
                console.log('Updating role:', this.editingRoleId, this.roleForm);
            }
            this.closeModal();
        },

        closeModal() {
            this.showCreateModal = false;
            this.showEditModal = false;
            this.editingRoleId = null;
            this.roleForm = { name: '', description: '' };
        },

        togglePermission(permissionId) {
            const index = this.selectedPermissions.indexOf(permissionId);
            if (index > -1) {
                this.selectedPermissions.splice(index, 1);
            } else {
                this.selectedPermissions.push(permissionId);
            }
        },

        savePermissions() {
            console.log('Saving permissions for role:', this.editingRoleId, this.selectedPermissions);
            this.showPermissionModal = false;
        }
    }));
});
</script>