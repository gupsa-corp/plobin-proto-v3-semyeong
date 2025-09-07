{{-- 조직 관리 메인 콘텐츠 --}}
<div class="organizations-content" style="padding: 24px;" x-data="organizationsManagement">
    
    {{-- 검색 및 필터 --}}
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- 검색 입력 --}}
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                           x-model="searchTerm"
                           @input="filterOrganizations"
                           placeholder="조직명 또는 도메인으로 검색..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            {{-- 필터 및 액션 버튼들 --}}
            <div class="flex items-center gap-3">
                {{-- 상태 필터 --}}
                <select x-model="statusFilter" 
                        @change="filterOrganizations"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">전체 상태</option>
                    <option value="active">활성</option>
                    <option value="suspended">일시중단</option>
                    <option value="inactive">비활성</option>
                </select>

                {{-- 새로고침 버튼 --}}
                <button @click="refreshData" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- 조직 목록 테이블 --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">전체 조직 목록</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            조직 정보
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            멤버/프로젝트
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            플랜/사용량
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            상태
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
                    <template x-for="org in filteredOrganizations" :key="org.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700" x-text="org.name.charAt(0)"></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="org.name"></div>
                                        <div class="text-sm text-gray-500" x-text="org.domain || 'domain.com'"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span x-text="org.members_count || '5'"></span> 멤버
                                </div>
                                <div class="text-sm text-gray-500">
                                    <span x-text="org.projects_count || '3'"></span> 프로젝트
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="org.plan || 'Pro'"></div>
                                <div class="text-sm text-gray-500" x-text="(org.usage || '70') + '% 사용'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full"
                                      :class="{
                                          'bg-green-100 text-green-800': org.status === 'active',
                                          'bg-yellow-100 text-yellow-800': org.status === 'suspended',
                                          'bg-red-100 text-red-800': org.status === 'inactive'
                                      }"
                                      x-text="org.status === 'active' ? '활성' : org.status === 'suspended' ? '일시중단' : '비활성'">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="org.created_at || '2024-01-15'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="viewOrganization(org.id)" 
                                            class="text-blue-600 hover:text-blue-900">상세</button>
                                    <button @click="editOrganization(org.id)" 
                                            class="text-gray-600 hover:text-gray-900">편집</button>
                                    <button @click="toggleOrganizationStatus(org.id)" 
                                            class="text-red-600 hover:text-red-900">
                                        <span x-text="org.status === 'active' ? '중단' : '활성화'"></span>
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
                    총 <span x-text="filteredOrganizations.length"></span>개 조직
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
    Alpine.data('organizationsManagement', () => ({
        searchTerm: '',
        statusFilter: '',
        organizations: [
            {
                id: 1,
                name: '테크스타트업코리아',
                domain: 'techstartup.co.kr',
                members_count: 12,
                projects_count: 8,
                plan: 'Enterprise',
                usage: 85,
                status: 'active',
                created_at: '2024-01-15'
            },
            {
                id: 2,
                name: 'AI개발연구소',
                domain: 'aidev.com',
                members_count: 7,
                projects_count: 5,
                plan: 'Pro',
                usage: 60,
                status: 'active',
                created_at: '2024-02-20'
            },
            {
                id: 3,
                name: '디지털솔루션',
                domain: 'digitalsol.co.kr',
                members_count: 15,
                projects_count: 12,
                plan: 'Enterprise',
                usage: 92,
                status: 'suspended',
                created_at: '2023-11-10'
            },
            {
                id: 4,
                name: '모바일앱스튜디오',
                domain: 'mobileapp.studio',
                members_count: 5,
                projects_count: 3,
                plan: 'Basic',
                usage: 35,
                status: 'active',
                created_at: '2024-03-05'
            }
        ],
        filteredOrganizations: [],

        init() {
            this.filteredOrganizations = this.organizations;
            console.log('Organizations management initialized');
        },

        filterOrganizations() {
            this.filteredOrganizations = this.organizations.filter(org => {
                const matchesSearch = !this.searchTerm || 
                    org.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                    (org.domain && org.domain.toLowerCase().includes(this.searchTerm.toLowerCase()));
                
                const matchesStatus = !this.statusFilter || org.status === this.statusFilter;
                
                return matchesSearch && matchesStatus;
            });
        },

        refreshData() {
            console.log('Refreshing organizations data...');
            // 실제 구현시 API 호출
        },

        viewOrganization(orgId) {
            console.log('Viewing organization:', orgId);
            // 상세 페이지로 이동 또는 모달 표시
        },

        editOrganization(orgId) {
            console.log('Editing organization:', orgId);
            // 편집 모달 표시
        },

        toggleOrganizationStatus(orgId) {
            const org = this.organizations.find(o => o.id === orgId);
            if (org) {
                org.status = org.status === 'active' ? 'suspended' : 'active';
                this.filterOrganizations();
            }
        }
    }));
});
</script>