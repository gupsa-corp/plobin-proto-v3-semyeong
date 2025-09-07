{{-- 사용자 관리 메인 콘텐츠 --}}
<div class="users-content" style="padding: 24px;" x-data="usersManagement">

    {{-- 사용자 목록 테이블 --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">전체 사용자 목록</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            사용자 정보
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            조직 소속
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            역할
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            상태
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            최근 접속
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            액션
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="user in filteredUsers" :key="user.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700" x-text="user.name.charAt(0)"></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="user.name"></div>
                                        <div class="text-sm text-gray-500" x-text="user.email"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span x-text="user.organizations_count || '0'"></span>개 조직
                                </div>
                                <div class="text-sm text-gray-500" x-text="user.primary_organization || '소속없음'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full"
                                      :class="{
                                          'bg-red-100 text-red-800': user.role === 'platform_admin',
                                          'bg-blue-100 text-blue-800': user.role === 'org_admin',
                                          'bg-gray-100 text-gray-800': user.role === 'member'
                                      }">
                                    <span x-text="user.role === 'platform_admin' ? '플랫폼 관리자' :
                                                user.role === 'org_admin' ? '조직 관리자' : '일반 멤버'"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full"
                                      :class="{
                                          'bg-green-100 text-green-800': user.status === 'active',
                                          'bg-yellow-100 text-yellow-800': user.status === 'suspended',
                                          'bg-red-100 text-red-800': user.status === 'inactive'
                                      }"
                                      x-text="user.status === 'active' ? '활성' : user.status === 'suspended' ? '일시중단' : '비활성'">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div x-text="user.last_login || '접속 기록 없음'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="viewUser(user.id)"
                                            class="text-blue-600 hover:text-blue-900">상세</button>
                                    <button @click="editUser(user.id)"
                                            class="text-gray-600 hover:text-gray-900">편집</button>
                                    <button @click="toggleUserStatus(user.id)"
                                            class="text-red-600 hover:text-red-900">
                                        <span x-text="user.status === 'active' ? '비활성화' : '활성화'"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- 페이지네이션 --}}
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    총 <span x-text="filteredUsers.length"></span>명의 사용자
                </div>
                <div class="flex items-center gap-2">
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50">이전</button>
                    <span class="px-3 py-1 text-sm bg-blue-500 text-white rounded">1</span>
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50">다음</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('usersManagement', () => ({
        searchTerm: '',
        statusFilter: '',
        roleFilter: '',
        users: [
            {
                id: 1,
                name: '김철수',
                email: 'kim@techstartup.co.kr',
                organizations_count: 2,
                primary_organization: '테크스타트업코리아',
                role: 'platform_admin',
                status: 'active',
                last_login: '2024-09-07 10:30'
            },
            {
                id: 2,
                name: '이영희',
                email: 'lee@aidev.com',
                organizations_count: 1,
                primary_organization: 'AI개발연구소',
                role: 'org_admin',
                status: 'active',
                last_login: '2024-09-06 15:20'
            },
            {
                id: 3,
                name: '박민수',
                email: 'park@digitalsol.co.kr',
                organizations_count: 1,
                primary_organization: '디지털솔루션',
                role: 'member',
                status: 'suspended',
                last_login: '2024-09-01 09:15'
            },
            {
                id: 4,
                name: '최지원',
                email: 'choi@mobileapp.studio',
                organizations_count: 1,
                primary_organization: '모바일앱스튜디오',
                role: 'org_admin',
                status: 'active',
                last_login: '2024-09-07 08:45'
            },
            {
                id: 5,
                name: '정수현',
                email: 'jung@example.com',
                organizations_count: 0,
                primary_organization: '소속없음',
                role: 'member',
                status: 'inactive',
                last_login: '2024-08-15 14:20'
            }
        ],
        filteredUsers: [],

        init() {
            this.filteredUsers = this.users;
            console.log('Users management initialized');
        },

        filterUsers() {
            this.filteredUsers = this.users.filter(user => {
                const matchesSearch = !this.searchTerm ||
                    user.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                    user.email.toLowerCase().includes(this.searchTerm.toLowerCase());

                const matchesStatus = !this.statusFilter || user.status === this.statusFilter;
                const matchesRole = !this.roleFilter || user.role === this.roleFilter;

                return matchesSearch && matchesStatus && matchesRole;
            });
        },

        refreshData() {
            console.log('Refreshing users data...');
            // 실제 구현시 API 호출
        },

        viewUser(userId) {
            console.log('Viewing user:', userId);
            // 상세 페이지로 이동 또는 모달 표시
        },

        editUser(userId) {
            console.log('Editing user:', userId);
            // 편집 모달 표시
        },

        toggleUserStatus(userId) {
            const user = this.users.find(u => u.id === userId);
            if (user) {
                user.status = user.status === 'active' ? 'inactive' : 'active';
                this.filterUsers();
            }
        }
    }));
});
</script>
