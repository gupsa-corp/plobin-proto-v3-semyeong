<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>관리자 대시보드</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="min-h-screen bg-gray-100">
        <div class="container mx-auto py-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">관리자 대시보드</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-blue-800">사용자 관리</h3>
                        <p class="text-blue-600 mt-2">구현필요</p>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-800">조직 관리</h3>
                        <p class="text-green-600 mt-2">구현필요</p>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-yellow-800">시스템 설정</h3>
                        <p class="text-yellow-600 mt-2">구현필요</p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">← 홈으로 돌아가기</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>