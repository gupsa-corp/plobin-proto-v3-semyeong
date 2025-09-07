{{-- 역할 관리 Livewire 컴포넌트 뷰 --}}
<div class="permission-roles-container" style="padding: 24px;" x-data="organizationPermissionRoles">

    {{-- 탭 네비게이션 --}}
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('organization.admin.permissions.overview', ['id' => $organizationId]) }}"
                   class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    권한 개요
                </a>
                <a href="{{ route('organization.admin.permissions.roles', ['id' => $organizationId]) }}"
                   class="whitespace-nowrap py-2 px-1 border-b-2 border-blue-500 text-blue-600 font-medium text-sm">
                    역할 관리
                </a>
                <a href="{{ route('organization.admin.permissions.management', ['id' => $organizationId]) }}"
                   class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    멤버 권한
                </a>
            </nav>
        </div>
    </div>

    {{-- 역할 관리 탭 --}}
    <div class="mb-6 flex justify-between items-center">
        <button @click="showCreateRoleModal = true"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            새 역할 추가
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">조직 역할 목록</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">역할명</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">권한 수</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">멤버 수</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">액션</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $role['label'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $role['description'] }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $role['permission_count'] }}개</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $role['member_count'] }}명</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="editRole('{{ $role['name'] }}')"
                                            class="text-blue-600 hover:text-blue-900">편집</button>
                                    <button onclick="manageRolePermissions('{{ $role['name'] }}')"
                                            class="text-green-600 hover:text-green-900">권한</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- 새 역할 추가 모달 --}}
    <div x-show="showCreateRoleModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showCreateRoleModal = false"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">새 역할 추가</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">역할 이름</label>
                            <input type="text" 
                                   x-model="newRole.name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="역할 이름을 입력하세요">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">역할 설명</label>
                            <textarea x-model="newRole.description"
                                     rows="3"
                                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                     placeholder="역할에 대한 설명을 입력하세요"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">권한 선택</label>
                            <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto border border-gray-200 rounded-md p-2">
                                @foreach($permissions as $category => $categoryPermissions)
                                    <div class="col-span-2">
                                        <h4 class="font-medium text-gray-900 mt-2 mb-1">{{ $category }}</h4>
                                    </div>
                                    @foreach($categoryPermissions as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" 
                                                   :value="'{{ $permission['name'] }}'"
                                                   x-model="newRole.permissions"
                                                   class="rounded border-gray-300">
                                            <span>{{ $permission['name'] }}</span>
                                        </label>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="createNewRole()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        생성
                    </button>
                    <button type="button" 
                            @click="showCreateRoleModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        취소
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('organizationPermissionRoles', () => ({
        showCreateRoleModal: false,
        newRole: {
            name: '',
            description: '',
            permissions: []
        },

        init() {
            console.log('Organization permission roles initialized');
        },

        editRole(roleName) {
            console.log('Editing role:', roleName);
            // TODO: 역할 편집 모달 구현
        },

        manageRolePermissions(roleName) {
            console.log('Managing permissions for role:', roleName);
            // TODO: 권한 관리 페이지로 이동
        },

        createNewRole() {
            if (!this.newRole.name) {
                alert('역할 이름을 입력해주세요.');
                return;
            }

            @this.call('createRole', this.newRole.name, this.newRole.description, this.newRole.permissions)
                .then(() => {
                    this.showCreateRoleModal = false;
                    this.newRole = { name: '', description: '', permissions: [] };
                    location.reload(); // 페이지 새로고침
                })
                .catch(error => {
                    alert('역할 생성 중 오류가 발생했습니다.');
                    console.error(error);
                });
        }
    }));
});

// 전역 함수
function editRole(roleName) {
    console.log('Edit role:', roleName);
    // TODO: 구현
}

function manageRolePermissions(roleName) {
    console.log('Manage role permissions:', roleName);
    // TODO: 구현
}
</script>