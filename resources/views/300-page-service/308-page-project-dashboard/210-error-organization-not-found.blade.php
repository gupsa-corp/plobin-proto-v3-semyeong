<!-- 조직이 없는 경우 에러 컴포넌트 -->
<div class="flex flex-col items-center justify-center min-h-96 bg-white rounded-lg border border-red-200 shadow-sm">
    <div class="text-center max-w-md">
        <div class="mb-6">
            <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>

        <h2 class="text-xl font-semibold text-red-800 mb-2">
            조직을 찾을 수 없습니다
        </h2>

        <p class="text-red-600 mb-6">
            요청한 조직이 존재하지 않거나 접근 권한이 없습니다.
        </p>

        <div class="space-y-3">
            <a href="/organizations"
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-6m-2-5.5v-.5m0 0V15a2 2 0 011.5-1.943L15 13V9a2 2 0 012-2h1a2 2 0 012 2v4l-1.943 1.5A2 2 0 0119 15v.5m0 0v.5M13 21h6"/>
                </svg>
                조직 목록으로 이동
            </a>
        </div>
    </div>
</div>
