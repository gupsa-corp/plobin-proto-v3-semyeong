<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $screen->title }} - 미리보기</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- AlpineJS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 m-0 p-0">
    <!-- 순수 커스텀 화면 컨텐츠만 표시 -->
    <div class="min-h-screen w-full">
        @if(isset($customContent) && !empty($customContent))
            {!! $customContent !!}
        @else
            <div class="p-8 text-center text-gray-500">
                <div class="text-6xl mb-4">📱</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">미리보기를 사용할 수 없습니다</h3>
                <p>{{ $screen->title }} 화면 파일을 찾을 수 없습니다.</p>
                <div class="mt-4 text-sm text-gray-400">
                    파일 경로: {{ $screen->getFullFilePath() }}
                </div>
            </div>
        @endif
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Filament Scripts -->
    @filamentScripts
    
    <!-- 새 창 전용 스타일 -->
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        
        .preview-container {
            width: 100%;
            min-height: 100vh;
        }
        
        /* 미리보기에서 스크롤바 스타일 개선 */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</body>
</html>