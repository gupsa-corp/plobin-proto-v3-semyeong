{{-- 조직 관리 메인 콘텐츠 --}}
<div class="organizations-content" style="padding: 24px;" >
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
                            멤버
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            프로젝트
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            생성일
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <span x-text="org.projects_count || '3'"></span> 프로젝트
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="org.created_at || '2024-01-15'"></span>
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
