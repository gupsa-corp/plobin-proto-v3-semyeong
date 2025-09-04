<main class="flex-1 p-6">
    <!-- 헤더 -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white">시스템 관리 대시보드</h1>
        <p class="text-gray-300">전체 시스템 현황을 모니터링하고 관리하세요</p>
    </div>

    <!-- 시스템 상태 카드 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="admin-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-300">시스템 상태</p>
                    <p class="text-2xl font-bold text-green-400">정상</p>
                </div>
                <div class="p-3 bg-green-600 rounded-full">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <div class="flex items-center text-sm text-green-400">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    가동시간 99.9%
                </div>
            </div>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-300">활성 사용자</p>
                    <p class="text-2xl font-bold text-blue-400">1,247</p>
                </div>
                <div class="p-3 bg-blue-600 rounded-full">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <div class="flex items-center text-sm text-blue-400">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    +12% 증가
                </div>
            </div>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-300">보안 이벤트</p>
                    <p class="text-2xl font-bold text-yellow-400">3</p>
                </div>
                <div class="p-3 bg-yellow-600 rounded-full">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <div class="flex items-center text-sm text-yellow-400">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    주의 필요
                </div>
            </div>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-300">서버 부하</p>
                    <p class="text-2xl font-bold text-green-400">23%</p>
                </div>
                <div class="p-3 bg-green-600 rounded-full">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <div class="flex items-center text-sm text-green-400">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                    정상 수준
                </div>
            </div>
        </div>
    </div>

    <!-- 메인 대시보드 콘텐츠 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 시스템 모니터링 -->
        <div class="lg:col-span-2">
            <div class="admin-card">
                <div class="p-6 border-b border-admin-border">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-white">실시간 모니터링</h3>
                        <div class="flex space-x-2">
                            <button class="btn-admin btn-admin-primary text-sm px-3 py-1">새로고침</button>
                            <button class="btn-admin text-sm px-3 py-1 bg-gray-600 text-white hover:bg-gray-500">설정</button>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- 차트 영역 -->
                    <div class="h-64 bg-gray-800 rounded-lg flex items-center justify-center mb-4">
                        <div class="text-center">
                            <svg class="h-16 w-16 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                            <p class="text-gray-400">실시간 성능 차트</p>
                            <p class="text-sm text-gray-500">Chart.js로 렌더링됩니다</p>
                        </div>
                    </div>
                    
                    <!-- 상세 메트릭 -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-400">CPU 사용률</p>
                            <p class="text-lg font-bold text-green-400">23%</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-400">메모리</p>
                            <p class="text-lg font-bold text-blue-400">67%</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-400">디스크</p>
                            <p class="text-lg font-bold text-yellow-400">45%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 시스템 로그 및 알림 -->
        <div class="lg:col-span-1">
            <div class="admin-card mb-6">
                <div class="p-6 border-b border-admin-border">
                    <h3 class="text-lg font-medium text-white">최근 활동</h3>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-green-400 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-300">시스템 백업 완료</p>
                                <p class="text-xs text-gray-500">10분 전</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-300">보안 스캔 감지</p>
                                <p class="text-xs text-gray-500">30분 전</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-300">새 사용자 등록</p>
                                <p class="text-xs text-gray-500">1시간 전</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-300">로그인 실패 증가</p>
                                <p class="text-xs text-gray-500">2시간 전</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 빠른 작업 -->
            <div class="admin-card">
                <div class="p-6 border-b border-admin-border">
                    <h3 class="text-lg font-medium text-white">빠른 작업</h3>
                </div>
                
                <div class="p-6 space-y-3">
                    <button class="w-full btn-admin btn-admin-primary text-left">
                        <svg class="h-4 w-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        백업 실행
                    </button>
                    
                    <button class="w-full btn-admin bg-gray-600 text-white hover:bg-gray-500 text-left">
                        <svg class="h-4 w-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        시스템 로그
                    </button>
                    
                    <button class="w-full btn-admin btn-admin-danger text-left" 
                            data-confirm="시스템을 재시작하시겠습니까?">
                        <svg class="h-4 w-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        시스템 재시작
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>