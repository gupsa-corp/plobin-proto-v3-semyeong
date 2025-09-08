<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>함수 자동화 워크플로우 - Plobin</title>
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
                       class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <span class="mr-2">🔗</span>
                        의존성 관리
                    </a>
                    <a href="{{ route('sandbox.function-automation') }}" 
                       class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600">
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
        @livewire('sandbox.function-automation')
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

        // Livewire 이벤트 리스너들
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('workflow-success', (event) => {
                alert('✅ ' + event.message);
            });

            Livewire.on('workflow-error', (event) => {
                alert('❌ ' + event.message);
            });

            Livewire.on('template-loaded', (event) => {
                alert('📝 ' + event.message);
            });

            Livewire.on('workflow-loaded', (event) => {
                alert('📂 ' + event.message);
            });

            Livewire.on('workflow-saved', (event) => {
                alert('💾 ' + event.message);
            });

            Livewire.on('workflow-reset', (event) => {
                if (confirm('워크플로우를 초기화하시겠습니까?')) {
                    alert('🔄 ' + event.message);
                }
            });

            Livewire.on('insert-function-code', (event) => {
                // 코드 에디터에 함수 코드 삽입 (기본 구현)
                const textarea = document.querySelector('textarea[wire\\:model\\.defer="workflowCode"]');
                if (textarea) {
                    const cursorPos = textarea.selectionStart;
                    const textBefore = textarea.value.substring(0, cursorPos);
                    const textAfter = textarea.value.substring(cursorPos);
                    
                    textarea.value = textBefore + '\n        ' + event.code + '\n' + textAfter;
                    textarea.focus();
                    
                    // Livewire 컴포넌트에 변경사항 알림
                    textarea.dispatchEvent(new Event('input'));
                }
            });
        });
    </script>
</body>
</html>