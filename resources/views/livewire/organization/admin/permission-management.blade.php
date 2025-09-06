<div>
    {{-- 권한 관리 메인 컨텐츠 --}}
    <div class="p-6">
        {{-- 페이지 헤더 --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">권한 관리</h2>
                    <p class="text-gray-600 mt-1">조직 내 권한 체계를 관리하고 설정할 수 있습니다</p>
                </div>
                <div class="flex gap-3">
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        권한 매트릭스 출력
                    </button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                        + 커스텀 역할 생성
                    </button>
                </div>
            </div>
        </div>

        {{-- 권한 레벨 개요 --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">권한 레벨 체계</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($permissionLevels as $level)
                <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer"
                     wire:click="selectPermission({{ $level['permissions'][0]->value }})">
                    <div class="flex items-center mb-3">
                        <div class="p-2 bg-{{ $level['color'] }}-100 rounded-lg mr-3">
                            <div class="w-6 h-6 bg-{{ $level['color'] }}-500 rounded"></div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $level['name'] }}</h4>
                            <p class="text-sm text-gray-500">레벨 {{ $level['level'] }} ({{ $level['range'] }})</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">{{ $level['description'] }}</p>
                    
                    <div class="space-y-2">
                        @foreach($level['permissions'] as $permission)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">{{ $permission->getLabel() }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $level['color'] }}-100 text-{{ $level['color'] }}-800">
                                {{ $permission->value }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 선택된 권한의 상세 기능 --}}
        @if($selectedPermission)
        <div class="mb-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">
                    {{ $selectedPermission->getLabel() }} ({{ $selectedPermission->value }}) 권한으로 가능한 작업
                </h3>
                
                @if(count($availableFeatures) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($availableFeatures as $category => $actions)
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <h4 class="font-medium text-gray-900 mb-3 capitalize">
                            {{ str_replace('_', ' ', $category) }}
                        </h4>
                        <div class="space-y-2">
                            @foreach($actions as $action => $description)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm text-gray-700">{{ $description }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <p class="text-gray-500">이 권한으로는 접근할 수 있는 기능이 없습니다.</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- 권한 매트릭스 테이블 --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">권한 매트릭스</h3>
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">기능 분류</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">작업</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">최소 권한</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">설명</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($permissionMatrix as $category => $actions)
                                @foreach($actions as $action => $requirements)
                                <tr class="hover:bg-gray-50">
                                    @if($loop->first)
                                    <td rowspan="{{ count($actions) }}" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-200 capitalize">
                                        {{ str_replace('_', ' ', $category) }}
                                    </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 capitalize">
                                        {{ str_replace('_', ' ', $action) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $requiredPermission = collect(OrganizationPermission::cases())
                                                ->first(function ($perm) use ($requirements) {
                                                    return $perm->value == $requirements[0];
                                                });
                                        @endphp
                                        @if($requiredPermission)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $requiredPermission->getBadgeColor() }}-100 text-{{ $requiredPermission->getBadgeColor() }}-800">
                                            {{ $requiredPermission->getShortLabel() }} ({{ $requirements[0] }})
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $requirements[1] }}</td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 권한 변경 로그 (추후 구현 예정) --}}
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">최근 권한 변경 내역</h3>
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-gray-500 text-sm">권한 변경 로그 기능은 추후 구현 예정입니다.</p>
            </div>
        </div>
    </div>
</div>