<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '통합 파일 에디터'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen">
        <div class="container-fluid px-4 py-8">
            <div class="max-w-full mx-auto">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-800">통합 파일 에디터</h2>
                        <p class="text-sm text-gray-600 mt-1">파일 매니저와 Monaco 에디터를 함께 사용하는 통합 개발 환경</p>
                    </div>
                    
                    <div class="p-8 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="mb-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">기능이 제거되었습니다</h3>
                            <p class="text-gray-600 mb-4">통합 파일 에디터는 더 이상 사용할 수 없습니다.</p>
                            <p class="text-sm text-gray-500 mb-6">대신 개별 파일 에디터를 이용해주세요.</p>
                            <a href="/sandbox/file-editor" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                파일 에디터로 이동
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>