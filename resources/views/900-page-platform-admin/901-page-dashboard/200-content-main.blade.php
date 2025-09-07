{{-- 플랫폼 관리자 대시보드 메인 콘텐츠 --}}
<div class="dashboard-content" style="padding: 24px;">

    {{-- 시스템 통계 카드들 (Livewire 컴포넌트) --}}
    @livewire('platform-admin.dashboard.system-stats')

    {{-- 메인 콘텐츠 영역 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- 최근 활동 로그 (Livewire 컴포넌트) --}}
        @livewire('platform-admin.dashboard.recent-activity')

        {{-- 시스템 리소스 모니터링 (개발 필요) --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">시스템 리소스</h3>
            </div>
            <div class="p-6">
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">시스템 리소스 모니터링</h4>
                    <p class="text-gray-500 mb-4">실시간 시스템 리소스 모니터링 기능이 개발 중입니다.</p>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        개발 필요
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
