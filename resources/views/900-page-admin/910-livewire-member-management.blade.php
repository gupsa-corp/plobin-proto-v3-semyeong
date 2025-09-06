<div>
    {{-- 회원 관리 메인 컨텐츠 --}}
    <div class="p-6">
        {{-- 페이지 헤더 --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">회원 관리</h2>
                    <p class="text-gray-600 mt-1">조직 구성원을 관리하고 초대할 수 있습니다</p>
                </div>
                <div class="flex gap-3">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                        + 멤버 초대
                    </button>
                </div>
            </div>
        </div>

        {{-- 통계 카드 --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">전체 멤버</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}명</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">활성 멤버</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['active'] }}명</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">대기 중</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] }}명</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">관리자급</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['admin'] }}명</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 검색 및 필터 --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text"
                               wire:model.live.debounce.300ms="searchTerm"
                               placeholder="이름, 이메일로 검색..."
                               class="pl-10 pr-3 py-2 border border-gray-300 rounded-lg w-full text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex gap-2">
                    <select wire:model.live="permissionFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">모든 권한</option>
                        @foreach($permissionLevels as $level => $name)
                            <option value="{{ $level }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">모든 상태</option>
                        <option value="active">활성</option>
                        <option value="pending">대기 중</option>
                        <option value="inactive">비활성</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- 회원 목록 테이블 --}}
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">멤버</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">권한</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">권한 레벨</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">상태</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">가입일</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">최근 활동</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">작업</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($filteredMembers as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-{{ $member['avatar_color'] }}-100 rounded-full flex items-center justify-center">
                                        <span class="text-{{ $member['avatar_color'] }}-600 font-medium text-sm">{{ $member['avatar_initial'] }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $member['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $member['email'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $member['permission']->getBadgeColor() }}-100 text-{{ $member['permission']->getBadgeColor() }}-800">
                                    {{ $member['permission']->getShortLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-900 font-medium">{{ $member['permission']->value }}</span>
                                    <span class="ml-2 text-xs text-gray-500">({{ $member['permission']->getLevelName() }})</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member['status'] == 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $member['status_name'] }}</span>
                                @elseif($member['status'] == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $member['status_name'] }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $member['status_name'] }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $member['joined_at'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $member['last_active'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    @if($member['status'] == 'pending')
                                        <button wire:click="resendInvitation({{ $member['id'] }})" class="text-blue-600 hover:text-blue-900">재초대</button>
                                        <button wire:click="removeMember({{ $member['id'] }})" class="text-red-600 hover:text-red-900">취소</button>
                                    @else
                                        <button class="text-blue-600 hover:text-blue-900">편집</button>
                                        <button wire:click="removeMember({{ $member['id'] }})" class="text-red-600 hover:text-red-900 ml-2">삭제</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 권한 정보 가이드 --}}
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-900 mb-2">권한 레벨 안내</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-yellow-800">0-99: 없음 (초대됨)</span>
                    <p class="text-yellow-700">조직에 초대되었으나 권한 부여 전</p>
                </div>
                <div>
                    <span class="font-medium text-blue-800">100-199: 사용자</span>
                    <p class="text-blue-700">기본 사용자, 프로젝트 참여</p>
                </div>
                <div>
                    <span class="font-medium text-green-800">200-299: 서비스 매니저</span>
                    <p class="text-green-700">프로젝트 관리, 팀 리딩</p>
                </div>
                <div>
                    <span class="font-medium text-purple-800">300-399: 조직 관리자</span>
                    <p class="text-purple-700">멤버 관리, 조직 설정</p>
                </div>
                <div>
                    <span class="font-medium text-red-800">400-499: 조직 소유자</span>
                    <p class="text-red-700">모든 조직 권한, 결제 관리</p>
                </div>
                <div>
                    <span class="font-medium text-gray-800">500-599: 플랫폼 관리자</span>
                    <p class="text-gray-700">시스템 전체 관리 권한</p>
                </div>
            </div>
        </div>

        {{-- 페이지네이션 --}}
        <div class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-500">
                총 {{ $filteredMembers->count() }}명 표시
            </div>
            <div class="flex gap-1">
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-500">이전</button>
                <button class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm">1</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700">2</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700">다음</button>
            </div>
        </div>
    </div>
</div>
