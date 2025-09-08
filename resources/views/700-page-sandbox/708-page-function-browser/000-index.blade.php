<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>함수 브라우저 - Plobin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen">
        @livewire('sandbox.function-browser')
    </div>
    
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