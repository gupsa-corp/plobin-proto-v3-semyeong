<!-- 인증 체크 로딩 -->
<div id="authLoading" class="flex items-center justify-center min-h-96">
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
    <span class="ml-3 text-gray-600">인증 확인 중...</span>
</div>

<!-- 인증되지 않은 사용자 -->
<div id="authRequired" class="hidden text-center py-20">
    <div class="mb-6">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-4">로그인이 필요합니다</h2>
    <p class="text-gray-600 mb-6">대시보드를 사용하려면 로그인해주세요.</p>
    <div class="space-x-4">
        <a href="/login" class="bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 transition duration-200">
            로그인
        </a>
        <a href="/signup" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-200 transition duration-200">
            회원가입
        </a>
    </div>
</div>