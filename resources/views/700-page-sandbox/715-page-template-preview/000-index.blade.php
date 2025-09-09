<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>템플릿 미리보기 - {{ $templateFolder }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen p-6">
        <!-- 미리보기 헤더 -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6 rounded">
            <div class="flex items-center">
                <div class="text-blue-500 text-2xl mr-3">👁️</div>
                <div>
                    <h1 class="text-lg font-semibold text-blue-800">템플릿 미리보기</h1>
                    <p class="text-blue-600 text-sm">{{ $templateFolder }} - 이것은 미리보기입니다. 실제 데이터와 다를 수 있습니다.</p>
                </div>
            </div>
        </div>

        <!-- 템플릿 콘텐츠 렌더링 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            {!! $templateContent !!}
        </div>
        
        <!-- 미리보기 푸터 -->
        <div class="text-center mt-6 text-gray-500 text-sm">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <p>🎨 <strong>템플릿 미리보기</strong> | ID: {{ $templateId }} | 폴더: {{ $templateFolder }}</p>
                <p class="mt-1">이 화면은 개발용 템플릿입니다. 실제 데이터는 다를 수 있습니다.</p>
            </div>
        </div>
    </div>
</body>
</html>