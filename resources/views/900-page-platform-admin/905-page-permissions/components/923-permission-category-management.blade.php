{{-- 권한 카테고리 관리 컴포넌트 --}}
<div class="permission-category-management" x-data="permissionCategoryManagement">
    
    {{-- 탭 네비게이션 (권한/카테고리) --}}
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeSubTab = 'permissions'"
                        :class="activeSubTab === 'permissions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    권한 관리
                </button>
                <button @click="activeSubTab = 'categories'"
                        :class="activeSubTab === 'categories' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    카테고리 관리
                </button>
            </nav>
        </div>
    </div>

    {{-- 권한 관리 탭 --}}
    <div x-show="activeSubTab === 'permissions'">
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button @click="showCreatePermissionModal = true" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    새 권한 추가
                </button>
                <select x-model="selectedCategory" 
                        @change="filterPermissions"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">전체 카테고리</option>
                    <template x-for="category in categories" :key="category.name">
                        <option :value="category.name" x-text="category.display_name"></option>
                    </template>
                </select>
            </div>
            
            <div class="flex items-center gap-2">
                <input type="text" 
                       x-model="searchTerm"
                       @input="filterPermissions"
                       placeholder="권한명으로 검색..."
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        {{-- 권한 목록 테이블 --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">권한 목록</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                권한명
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                카테고리
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                할당된 역할
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
                        <template x-for="permission in filteredPermissions" :key="permission.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900" x-text="permission.display_name"></div>
                                        <div class="text-sm text-gray-500" x-text="permission.name"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800"
                                          x-text="permission.category_display_name"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900" x-text="permission.roles_count + '개 역할'"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span x-text="permission.created_at"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editPermission(permission.id)" 
                                                class="text-blue-600 hover:text-blue-900">편집</button>
                                        <button @click="deletePermission(permission.id)" 
                                                class="text-red-600 hover:text-red-900">삭제</button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 카테고리 관리 탭 --}}
    <div x-show="activeSubTab === 'categories'">
        <div class="mb-6 flex justify-between items-center">
            <button @click="showCreateCategoryModal = true" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                새 카테고리 추가
            </button>
        </div>

        {{-- 카테고리 목록 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="category in categories" :key="category.id">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900" x-text="category.display_name"></h3>
                            <p class="text-sm text-gray-500 mt-1" x-text="category.description"></p>
                            <div class="mt-4 text-sm text-gray-600">
                                <span x-text="category.permissions_count + '개 권한'"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="editCategory(category.id)" 
                                    class="text-blue-600 hover:text-blue-900">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                            </button>
                            <button @click="deleteCategory(category.id)" 
                                    :disabled="category.permissions_count > 0"
                                    :class="category.permissions_count > 0 ? 'text-gray-400 cursor-not-allowed' : 'text-red-600 hover:text-red-900'">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- 권한 생성/편집 모달 --}}
    <div x-show="showCreatePermissionModal || showEditPermissionModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4" 
                x-text="showCreatePermissionModal ? '새 권한 추가' : '권한 편집'"></h3>
            
            <form @submit.prevent="savePermission">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">표시명</label>
                        <input type="text" 
                               x-model="permissionForm.display_name"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">권한명 (시스템)</label>
                        <input type="text" 
                               x-model="permissionForm.name"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">카테고리</label>
                        <select x-model="permissionForm.category"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">선택하세요</option>
                            <template x-for="category in categories" :key="category.name">
                                <option :value="category.name" x-text="category.display_name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" 
                            @click="closePermissionModal"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        취소
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <span x-text="showCreatePermissionModal ? '추가' : '저장'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 카테고리 생성/편집 모달 --}}
    <div x-show="showCreateCategoryModal || showEditCategoryModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4" 
                x-text="showCreateCategoryModal ? '새 카테고리 추가' : '카테고리 편집'"></h3>
            
            <form @submit.prevent="saveCategory">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">카테고리명 (시스템)</label>
                        <input type="text" 
                               x-model="categoryForm.name"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">표시명</label>
                        <input type="text" 
                               x-model="categoryForm.display_name"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">설명</label>
                        <textarea x-model="categoryForm.description"
                                  rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" 
                            @click="closeCategoryModal"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        취소
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <span x-text="showCreateCategoryModal ? '추가' : '저장'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('permissionCategoryManagement', () => ({
        activeSubTab: 'permissions',
        searchTerm: '',
        selectedCategory: '',
        permissions: [
            {
                id: 1,
                name: 'invite members',
                display_name: '멤버 초대',
                category: 'member_management',
                category_display_name: '멤버 관리',
                roles_count: 3,
                created_at: '2024-01-01'
            },
            {
                id: 2,
                name: 'remove members',
                display_name: '멤버 제거',
                category: 'member_management',
                category_display_name: '멤버 관리',
                roles_count: 2,
                created_at: '2024-01-01'
            },
            {
                id: 3,
                name: 'create projects',
                display_name: '프로젝트 생성',
                category: 'project_management',
                category_display_name: '프로젝트 관리',
                roles_count: 4,
                created_at: '2024-01-15'
            }
        ],
        filteredPermissions: [],
        categories: [
            {
                id: 1,
                name: 'member_management',
                display_name: '멤버 관리',
                description: '조직 멤버 관련 권한들',
                permissions_count: 5
            },
            {
                id: 2,
                name: 'project_management',
                display_name: '프로젝트 관리',
                description: '프로젝트 생성, 수정, 삭제 권한들',
                permissions_count: 8
            },
            {
                id: 3,
                name: 'billing_management',
                display_name: '결제 관리',
                description: '결제 및 구독 관련 권한들',
                permissions_count: 6
            }
        ],
        showCreatePermissionModal: false,
        showEditPermissionModal: false,
        showCreateCategoryModal: false,
        showEditCategoryModal: false,
        editingPermissionId: null,
        editingCategoryId: null,
        permissionForm: {
            display_name: '',
            name: '',
            category: ''
        },
        categoryForm: {
            name: '',
            display_name: '',
            description: ''
        },

        init() {
            this.filteredPermissions = this.permissions;
            console.log('Permission category management initialized');
        },

        filterPermissions() {
            this.filteredPermissions = this.permissions.filter(permission => {
                const matchesSearch = !this.searchTerm || 
                    permission.display_name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                    permission.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                
                const matchesCategory = !this.selectedCategory || permission.category === this.selectedCategory;
                
                return matchesSearch && matchesCategory;
            });
        },

        editPermission(permissionId) {
            const permission = this.permissions.find(p => p.id === permissionId);
            if (permission) {
                this.editingPermissionId = permissionId;
                this.permissionForm = {
                    display_name: permission.display_name,
                    name: permission.name,
                    category: permission.category
                };
                this.showEditPermissionModal = true;
            }
        },

        deletePermission(permissionId) {
            if (confirm('정말로 이 권한을 삭제하시겠습니까?')) {
                console.log('Deleting permission:', permissionId);
            }
        },

        savePermission() {
            if (this.showCreatePermissionModal) {
                console.log('Creating new permission:', this.permissionForm);
            } else {
                console.log('Updating permission:', this.editingPermissionId, this.permissionForm);
            }
            this.closePermissionModal();
        },

        closePermissionModal() {
            this.showCreatePermissionModal = false;
            this.showEditPermissionModal = false;
            this.editingPermissionId = null;
            this.permissionForm = { display_name: '', name: '', category: '' };
        },

        editCategory(categoryId) {
            const category = this.categories.find(c => c.id === categoryId);
            if (category) {
                this.editingCategoryId = categoryId;
                this.categoryForm = {
                    name: category.name,
                    display_name: category.display_name,
                    description: category.description
                };
                this.showEditCategoryModal = true;
            }
        },

        deleteCategory(categoryId) {
            if (confirm('정말로 이 카테고리를 삭제하시겠습니까?')) {
                console.log('Deleting category:', categoryId);
            }
        },

        saveCategory() {
            if (this.showCreateCategoryModal) {
                console.log('Creating new category:', this.categoryForm);
            } else {
                console.log('Updating category:', this.editingCategoryId, this.categoryForm);
            }
            this.closeCategoryModal();
        },

        closeCategoryModal() {
            this.showCreateCategoryModal = false;
            this.showEditCategoryModal = false;
            this.editingCategoryId = null;
            this.categoryForm = { name: '', display_name: '', description: '' };
        }
    }));
});
</script>