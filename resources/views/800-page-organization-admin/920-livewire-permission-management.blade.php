{{-- 조직 관리자용 권한 관리 메인 컴포넌트 --}}
<div class="permission-management-container" style="padding: 24px;" x-data="organizationPermissionManagement">

    {{-- 탭 네비게이션 --}}
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    권한 개요
                </button>
                <button @click="activeTab = 'roles'"
                        :class="activeTab === 'roles' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    역할 관리
                </button>
                <button @click="activeTab = 'members'"
                        :class="activeTab === 'members' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    멤버 권한
                </button>
            </nav>
        </div>
    </div>

    {{-- 탭 컨텐츠 --}}
    <div class="tab-content">
        {{-- 권한 개요 탭 --}}
        <div x-show="activeTab === 'overview'">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">

                        <div class="ml-4">
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['totalMembers'] }}</div>
                            <div class="text-sm text-gray-600">총 멤버</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">

                        <div class="ml-4">
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['totalRoles'] }}</div>
                            <div class="text-sm text-gray-600">활성 역할</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">

                        <div class="ml-4">
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['totalPermissions'] }}</div>
                            <div class="text-sm text-gray-600">사용 가능한 권한</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 멤버별 권한 현황 --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">멤버별 권한 현황</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-2">멤버</th>
                                    <th class="text-center py-2">역할</th>
                                    <th class="text-center py-2">멤버관리</th>
                                    <th class="text-center py-2">프로젝트관리</th>
                                    <th class="text-center py-2">결제관리</th>
                                    <th class="text-center py-2">조직설정</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($members as $member)
                                    <tr>
                                        <td class="py-2">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                                    <span class="text-xs font-medium">{{ substr($member['name'], 0, 1) }}</span>
                                                </div>
                                                <span>{{ $member['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center py-2">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $member['primary_role'] }}</span>
                                        </td>
                                        <td class="text-center py-2">
                                            @if($member['permissions']['member'])
                                                <span class="text-green-600">✓</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-2">
                                            @if($member['permissions']['project'])
                                                <span class="text-green-600">✓</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-2">
                                            @if($member['permissions']['billing'])
                                                <span class="text-green-600">✓</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-2">
                                            @if($member['permissions']['organization'])
                                                <span class="text-green-600">✓</span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- 역할 관리 탭 --}}
        <div x-show="activeTab === 'roles'">
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
        </div>

        {{-- 멤버 권한 탭 --}}
        <div x-show="activeTab === 'members'">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">멤버 권한 관리</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">멤버</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">현재 역할</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">가입일</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">액션</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($members as $member)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-sm font-medium text-gray-700">{{ substr($member['name'], 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $member['name'] }}</div>
                                                <div class="text-sm text-gray-500">{{ $member['email'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $member['primary_role'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span>{{ $member['joined_at'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="showRoleChangeModal({{ $member['id'] }}, '{{ $member['name'] }}', '{{ $member['primary_role'] }}')"
                                                class="text-blue-600 hover:text-blue-900">역할 변경</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 역할 변경 모달 --}}
    <div x-show="showRoleChangeModal"
         class="fixed inset-0 z-50 overflow-y-auto"
         x-cloak
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showRoleChangeModal = false"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">멤버 역할 변경</h3>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600" x-text="'멤버: ' + selectedMember.name"></p>
                        <p class="text-sm text-gray-600" x-text="'현재 역할: ' + selectedMember.currentRole"></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">새 역할</label>
                        <select x-model="selectedMember.newRole"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">역할을 선택하세요</option>
                            @foreach($roles as $role)
                                <option value="{{ $role['name'] }}">{{ $role['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            @click="confirmRoleChange()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        변경
                    </button>
                    <button type="button"
                            @click="showRoleChangeModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        취소
                    </button>
                </div>
            </div>
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
    Alpine.data('organizationPermissionManagement', () => ({
        activeTab: 'overview',
        showCreateRoleModal: false,
        showRoleChangeModal: false,
        selectedMember: {
            id: null,
            name: '',
            currentRole: '',
            newRole: ''
        },
        newRole: {
            name: '',
            description: '',
            permissions: []
        },

        init() {
            console.log('Organization permission management initialized');
        },

        editRole(roleId) {
            console.log('Editing role:', roleId);
            // TODO: 역할 편집 모달 구현
        },

        manageRolePermissions(roleId) {
            console.log('Managing permissions for role:', roleId);
            // TODO: 권한 관리 페이지로 이동
        },

        showRoleChangeDialog(memberId, memberName, currentRole) {
            this.selectedMember = {
                id: memberId,
                name: memberName,
                currentRole: currentRole,
                newRole: ''
            };
            this.showRoleChangeModal = true;
        },

        confirmRoleChange() {
            if (!this.selectedMember.newRole) {
                alert('새 역할을 선택해주세요.');
                return;
            }

            @this.call('changeMemberRole', this.selectedMember.id, this.selectedMember.newRole)
                .then(() => {
                    this.showRoleChangeModal = false;
                    location.reload(); // 페이지 새로고침
                })
                .catch(error => {
                    alert('역할 변경 중 오류가 발생했습니다.');
                    console.error(error);
                });
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

// 전역 함수로 모달 호출
function showRoleChangeModal(memberId, memberName, currentRole) {
    const component = Alpine.$data(document.querySelector('[x-data="organizationPermissionManagement"]'));
    component.showRoleChangeDialog(memberId, memberName, currentRole);
}
</script>
