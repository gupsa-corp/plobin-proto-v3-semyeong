<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>함수 의존성 관리 - Plobin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen">
        {{-- Tab Navigation --}}
        <div class="bg-white border-b border-gray-200">
            <div class="px-6 py-3">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('sandbox.function-browser') }}" 
                       class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <span class="mr-2">📚</span>
                        함수 브라우저
                    </a>
                    <a href="{{ route('sandbox.function-creator') }}" 
                       class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <span class="mr-2">✨</span>
                        함수 생성
                    </a>
                    <a href="{{ route('sandbox.function-dependencies') }}" 
                       class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600">
                        <span class="mr-2">🔗</span>
                        의존성 관리
                    </a>
                    <a href="{{ route('sandbox.function-automation') }}" 
                       class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <span class="mr-2">⚡</span>
                        자동화
                    </a>
                    <a href="{{ route('sandbox.function-templates') }}" 
                       class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <span class="mr-2">🏪</span>
                        템플릿
                    </a>
                </nav>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="h-full overflow-hidden" style="height: calc(100vh - 140px);">
            @livewire('sandbox.function-dependencies')
        </div>
    </div>
    
    <!-- External Libraries -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="{{ asset('js/function-dependencies.js') }}"></script>
    
    <!-- Livewire Scripts (includes Alpine.js) -->
    @livewireScripts
    
    <!-- Alpine.js initialization fix -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 이미 Alpine이 로드된 경우 재초기화
            if (typeof Alpine !== 'undefined') {
                console.log('Alpine.js detected, ensuring proper initialization...');
                
                // $wire 매직 프로퍼티 재등록
                Alpine.magic('wire', (el) => {
                    const wireId = el.closest('[wire\\:id]')?.getAttribute('wire:id');
                    if (wireId && window.Livewire) {
                        return window.Livewire.find(wireId);
                    }
                    return null;
                });
                
                // Alpine 강제 재시작 (필요한 경우)
                if (!Alpine.version) {
                    Alpine.start();
                }
            }
        });
    </script>
</body>
</html>