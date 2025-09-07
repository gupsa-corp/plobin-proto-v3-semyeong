{{-- 권한 개요 컴포넌트 --}}
<div class="permission-overview" x-data="permissionOverview">
    
    {{-- 시스템 통계 카드들 --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900" x-text="stats.totalRoles"></div>
                    <div class="text-sm text-gray-600">전체 역할</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900" x-text="stats.totalPermissions"></div>
                    <div class="text-sm text-gray-600">전체 권한</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900" x-text="stats.totalRules"></div>
                    <div class="text-sm text-gray-600">동적 규칙</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900" x-text="stats.totalCategories"></div>
                    <div class="text-sm text-gray-600">권한 카테고리</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 권한 매트릭스 및 최근 변경사항 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- 권한 매트릭스 --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">권한 매트릭스</h3>
                <button @click="refreshMatrix" 
                        class="text-sm text-blue-600 hover:text-blue-500">새로고침</button>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2">역할</th>
                                <th class="text-center py-2">멤버</th>
                                <th class="text-center py-2">프로젝트</th>
                                <th class="text-center py-2">결제</th>
                                <th class="text-center py-2">조직</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="role in permissionMatrix" :key="role.name">
                                <tr>
                                    <td class="py-2 font-medium" x-text="role.name"></td>
                                    <td class="text-center py-2">
                                        <span x-show="role.permissions.member" class="text-green-600">✓</span>
                                        <span x-show="!role.permissions.member" class="text-gray-300">-</span>
                                    </td>
                                    <td class="text-center py-2">
                                        <span x-show="role.permissions.project" class="text-green-600">✓</span>
                                        <span x-show="!role.permissions.project" class="text-gray-300">-</span>
                                    </td>
                                    <td class="text-center py-2">
                                        <span x-show="role.permissions.billing" class="text-green-600">✓</span>
                                        <span x-show="!role.permissions.billing" class="text-gray-300">-</span>
                                    </td>
                                    <td class="text-center py-2">
                                        <span x-show="role.permissions.organization" class="text-green-600">✓</span>
                                        <span x-show="!role.permissions.organization" class="text-gray-300">-</span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 최근 변경사항 --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">최근 변경사항</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <template x-for="change in recentChanges" :key="change.id">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 rounded-full mt-2"
                                     :class="{
                                         'bg-green-500': change.type === 'create',
                                         'bg-blue-500': change.type === 'update',
                                         'bg-red-500': change.type === 'delete'
                                     }"></div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm text-gray-900" x-text="change.description"></div>
                                <div class="text-xs text-gray-500">
                                    <span x-text="change.user"></span> - <span x-text="change.time"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="inline-flex items-center text-sm text-gray-500">
                        <span>모든 변경사항 보기</span>
                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">개발 필요</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('permissionOverview', () => ({
        stats: {
            totalRoles: 8,
            totalPermissions: 24,
            totalRules: 12,
            totalCategories: 5
        },
        permissionMatrix: [
            {
                name: '플랫폼 관리자',
                permissions: { member: true, project: true, billing: true, organization: true }
            },
            {
                name: '조직 관리자',
                permissions: { member: true, project: true, billing: true, organization: false }
            },
            {
                name: '프로젝트 관리자',
                permissions: { member: false, project: true, billing: false, organization: false }
            },
            {
                name: '일반 멤버',
                permissions: { member: false, project: false, billing: false, organization: false }
            }
        ],
        recentChanges: [
            {
                id: 1,
                type: 'create',
                description: '새로운 역할 "팀 리더"가 생성됨',
                user: '김관리자',
                time: '2분 전'
            },
            {
                id: 2,
                type: 'update',
                description: '"프로젝트 관리자" 역할의 권한이 수정됨',
                user: '이관리자',
                time: '15분 전'
            },
            {
                id: 3,
                type: 'create',
                description: '동적 규칙 "특별 프로젝트 접근"이 추가됨',
                user: '박관리자',
                time: '1시간 전'
            },
            {
                id: 4,
                type: 'delete',
                description: '사용하지 않는 권한 "legacy_access"가 삭제됨',
                user: '최관리자',
                time: '2시간 전'
            }
        ],

        init() {
            console.log('Permission overview initialized');
        },

        refreshMatrix() {
            console.log('Refreshing permission matrix...');
            // 실제 구현시 API 호출
        }
    }));
});
</script>