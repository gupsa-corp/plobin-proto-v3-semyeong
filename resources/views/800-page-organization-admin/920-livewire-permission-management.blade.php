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
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-gray-900" x-text="stats.totalMembers"></div>
                            <div class="text-sm text-gray-600">총 멤버</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-gray-900" x-text="stats.totalRoles"></div>
                            <div class="text-sm text-gray-600">활성 역할</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-gray-900" x-text="stats.totalPermissions"></div>
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
                                <template x-for="member in members" :key="member.id">
                                    <tr>
                                        <td class="py-2">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                                    <span class="text-xs font-medium" x-text="member.name.charAt(0)"></span>
                                                </div>
                                                <span x-text="member.name"></span>
                                            </div>
                                        </td>
                                        <td class="text-center py-2">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800" x-text="member.role"></span>
                                        </td>
                                        <td class="text-center py-2">
                                            <span x-show="member.permissions.member" class="text-green-600">✓</span>
                                            <span x-show="!member.permissions.member" class="text-gray-300">-</span>
                                        </td>
                                        <td class="text-center py-2">
                                            <span x-show="member.permissions.project" class="text-green-600">✓</span>
                                            <span x-show="!member.permissions.project" class="text-gray-300">-</span>
                                        </td>
                                        <td class="text-center py-2">
                                            <span x-show="member.permissions.billing" class="text-green-600">✓</span>
                                            <span x-show="!member.permissions.billing" class="text-gray-300">-</span>
                                        </td>
                                        <td class="text-center py-2">
                                            <span x-show="member.permissions.organization" class="text-green-600">✓</span>
                                            <span x-show="!member.permissions.organization" class="text-gray-300">-</span>
                                        </td>
                                    </tr>
                                </template>
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
                            <template x-for="role in organizationRoles" :key="role.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900" x-text="role.name"></div>
                                            <div class="text-sm text-gray-500" x-text="role.description"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" x-text="role.permissions_count + '개'"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" x-text="role.members_count + '명'"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="editRole(role.id)" 
                                                    class="text-blue-600 hover:text-blue-900">편집</button>
                                            <button @click="manageRolePermissions(role.id)" 
                                                    class="text-green-600 hover:text-green-900">권한</button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
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
                            <template x-for="member in members" :key="member.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-sm font-medium text-gray-700" x-text="member.name.charAt(0)"></span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900" x-text="member.name"></div>
                                                <div class="text-sm text-gray-500" x-text="member.email"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800" x-text="member.role"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span x-text="member.joined_at"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="changeMemberRole(member.id)" 
                                                class="text-blue-600 hover:text-blue-900">역할 변경</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
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
        stats: {
            totalMembers: 8,
            totalRoles: 4,
            totalPermissions: 15
        },
        members: [
            {
                id: 1,
                name: '김철수',
                email: 'kim@company.com',
                role: '조직 관리자',
                joined_at: '2024-01-15',
                permissions: { member: true, project: true, billing: true, organization: true }
            },
            {
                id: 2,
                name: '이영희',
                email: 'lee@company.com',
                role: '프로젝트 관리자',
                joined_at: '2024-02-01',
                permissions: { member: false, project: true, billing: false, organization: false }
            },
            {
                id: 3,
                name: '박민수',
                email: 'park@company.com',
                role: '일반 멤버',
                joined_at: '2024-02-15',
                permissions: { member: false, project: false, billing: false, organization: false }
            }
        ],
        organizationRoles: [
            {
                id: 1,
                name: '조직 관리자',
                description: '조직의 모든 권한을 가진 관리자',
                permissions_count: 12,
                members_count: 2
            },
            {
                id: 2,
                name: '프로젝트 관리자',
                description: '프로젝트 관리 권한을 가진 역할',
                permissions_count: 8,
                members_count: 3
            },
            {
                id: 3,
                name: '일반 멤버',
                description: '기본적인 조직 참여 권한',
                permissions_count: 3,
                members_count: 15
            }
        ],

        init() {
            console.log('Organization permission management initialized');
        },

        editRole(roleId) {
            console.log('Editing role:', roleId);
        },

        manageRolePermissions(roleId) {
            console.log('Managing permissions for role:', roleId);
        },

        changeMemberRole(memberId) {
            console.log('Changing role for member:', memberId);
        }
    }));
});
</script>