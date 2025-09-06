{{-- 조직 관리 대시보드 메인 컨텐츠 --}}
<div class="p-6">
    {{-- 관리 개요 --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">조직 관리 대시보드</h2>
        <p class="text-gray-600">Tech Corp 조직의 전반적인 현황을 확인하고 관리하세요</p>
    </div>

    {{-- 주요 통계 카드 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">총 멤버</p>
                    <p class="text-2xl font-semibold text-gray-900">24명</p>
                    <p class="text-sm text-green-600 mt-1">+2명 이번 달</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">활성 프로젝트</p>
                    <p class="text-2xl font-semibold text-gray-900">8개</p>
                    <p class="text-sm text-blue-600 mt-1">진행률 65%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">월 사용료</p>
                    <p class="text-2xl font-semibold text-gray-900">₩99,000</p>
                    <p class="text-sm text-gray-500 mt-1">Pro 플랜</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">스토리지 사용량</p>
                    <p class="text-2xl font-semibold text-gray-900">145GB</p>
                    <p class="text-sm text-gray-500 mt-1">/ 500GB</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 빠른 액션 카드 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="/organizations/1/admin/members" class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow group">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-200 transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">회원 관리</h3>
                <p class="text-sm text-gray-600">조직 구성원 초대 및 관리</p>
            </div>
        </a>

        <a href="/organizations/1/admin/permissions" class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow group">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-green-200 transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">권한 관리</h3>
                <p class="text-sm text-gray-600">역할 및 권한 설정</p>
            </div>
        </a>

        <a href="/organizations/1/admin/billing" class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow group">
            <div class="text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-200 transition-colors">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">결제 관리</h3>
                <p class="text-sm text-gray-600">요금제 및 결제 정보</p>
            </div>
        </a>

        <a href="/organizations/1/admin/projects" class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow group">
            <div class="text-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">프로젝트 관리</h3>
                <p class="text-sm text-gray-600">조직 프로젝트 현황</p>
            </div>
        </a>
    </div>

    {{-- 최근 활동 및 알림 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- 최근 활동 --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">최근 활동</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm text-gray-900">새로운 멤버 <strong>이영희</strong>님이 조직에 합류했습니다</p>
                            <p class="text-xs text-gray-500 mt-1">2시간 전</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm text-gray-900">프로젝트 <strong>웹 애플리케이션 리뉴얼</strong>이 65% 진행되었습니다</p>
                            <p class="text-xs text-gray-500 mt-1">4시간 전</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm text-gray-900">Pro 플랜 월간 결제가 완료되었습니다</p>
                            <p class="text-xs text-gray-500 mt-1">1일 전</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm text-gray-900">새로운 역할 <strong>프로젝트 매니저</strong>가 생성되었습니다</p>
                            <p class="text-xs text-gray-500 mt-1">2일 전</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">모든 활동 보기 →</a>
                </div>
            </div>
        </div>

        {{-- 알림 및 공지사항 --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">알림 및 공지사항</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">스토리지 용량 알림</p>
                            <p class="text-sm text-gray-600 mt-1">현재 스토리지 사용량이 29%입니다. 용량 관리를 권장합니다.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">새로운 기능 업데이트</p>
                            <p class="text-sm text-gray-600 mt-1">프로젝트 관리 기능이 개선되었습니다. 새로운 기능을 확인해보세요.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">보안 업데이트 완료</p>
                            <p class="text-sm text-gray-600 mt-1">최신 보안 패치가 적용되어 시스템이 더욱 안전해졌습니다.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">모든 알림 보기 →</a>
                </div>
            </div>
        </div>
    </div>
</div>