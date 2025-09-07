{{-- 플랫폼 관리자 대시보드 메인 콘텐츠 --}}
<div class="dashboard-content" style="padding: 24px;" x-data="platformDashboard">
    
    {{-- 시스템 통계 카드들 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- 전체 조직 수 --}}
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
                    <div class="text-2xl font-bold text-gray-900">{{ $totalOrganizations ?? '12' }}</div>
                    <div class="text-sm text-gray-600">전체 조직</div>
                </div>
            </div>
        </div>

        {{-- 전체 사용자 수 --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $totalUsers ?? '147' }}</div>
                    <div class="text-sm text-gray-600">전체 사용자</div>
                </div>
            </div>
        </div>

        {{-- 전체 프로젝트 수 --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $totalProjects ?? '89' }}</div>
                    <div class="text-sm text-gray-600">전체 프로젝트</div>
                </div>
            </div>
        </div>

        {{-- 시스템 상태 --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-green-600">정상</div>
                    <div class="text-sm text-gray-600">시스템 상태</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 메인 콘텐츠 영역 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- 최근 활동 로그 --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">최근 활동</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div class="ml-3">
                            <div class="text-sm text-gray-900">새 조직이 생성되었습니다</div>
                            <div class="text-xs text-gray-500">테크스타트업코리아 - 2분 전</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div class="ml-3">
                            <div class="text-sm text-gray-900">사용자가 가입했습니다</div>
                            <div class="text-xs text-gray-500">김개발 (kim@example.com) - 5분 전</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                        <div class="ml-3">
                            <div class="text-sm text-gray-900">새 프로젝트가 생성되었습니다</div>
                            <div class="text-xs text-gray-500">AI 챗봇 프로젝트 - 10분 전</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                        <div class="ml-3">
                            <div class="text-sm text-gray-900">시스템 업데이트 완료</div>
                            <div class="text-xs text-gray-500">버전 2.1.0 - 1시간 전</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-500">모든 활동 보기</a>
                </div>
            </div>
        </div>

        {{-- 시스템 리소스 사용량 --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">시스템 리소스</h3>
            </div>
            <div class="p-6 space-y-6">
                {{-- CPU 사용률 --}}
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>CPU 사용률</span>
                        <span>23%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 23%"></div>
                    </div>
                </div>

                {{-- 메모리 사용률 --}}
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>메모리 사용률</span>
                        <span>67%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 67%"></div>
                    </div>
                </div>

                {{-- 스토리지 사용률 --}}
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>스토리지 사용률</span>
                        <span>45%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>

                {{-- 네트워크 트래픽 --}}
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>네트워크 트래픽</span>
                        <span>15 MB/s</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 30%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 빠른 액션 버튼들 --}}
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">빠른 액션</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('platform.admin.organizations') }}" 
               class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                조직 관리
            </a>
            <a href="{{ route('platform.admin.users') }}" 
               class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                사용자 관리
            </a>
            <a href="{{ route('platform.admin.permissions') }}" 
               class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                </svg>
                권한 관리
            </a>
            <a href="{{ route('platform.admin.system-settings') }}" 
               class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                시스템 설정
            </a>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('platformDashboard', () => ({
        init() {
            console.log('Platform admin dashboard initialized');
        }
    }));
});
</script>