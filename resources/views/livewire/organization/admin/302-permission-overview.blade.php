{{-- 권한 개요 Livewire 컴포넌트 뷰 --}}
<div class="permission-overview-container" style="padding: 24px;">

    {{-- 탭 네비게이션 --}}
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('organization.admin.permissions.overview', ['id' => $organizationId]) }}"
                   class="whitespace-nowrap py-2 px-1 border-b-2 border-blue-500 text-blue-600 font-medium text-sm">
                    권한 개요
                </a>
                <a href="{{ route('organization.admin.permissions.roles', ['id' => $organizationId]) }}"
                   class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    역할 관리
                </a>
                <a href="{{ route('organization.admin.permissions.management', ['id' => $organizationId]) }}"
                   class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    멤버 권한
                </a>
            </nav>
        </div>
    </div>

    {{-- 통계 카드 --}}
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