<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>함수 템플릿 - Plobin</title>
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
                       class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <span class="mr-2">⚡</span>
                        자동화
                    </a>
                    <a href="{{ route('sandbox.function-templates') }}" 
                       class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600">
                        <span class="mr-2">🏪</span>
                        템플릿
                    </a>
                </nav>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="h-full p-6 overflow-auto" style="height: calc(100vh - 140px);">
            <div class="max-w-6xl mx-auto">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">함수 템플릿 라이브러리</h3>
                    <p class="text-gray-600">사용 가능한 함수 템플릿을 둘러보고 새 함수 생성에 활용하세요.</p>
                </div>

                {{-- Template Service Loading --}}
                <div id="template-content">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">⚙️</div>
                        <p class="text-gray-600">템플릿 서비스를 로드하는 중입니다...</p>
                    </div>
                </div>

                {{-- Placeholder for when service is available --}}
                <div id="template-grid" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Templates will be loaded here dynamically --}}
                    </div>
                </div>
            </div>
        </div>
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

            // Mock template data loading simulation
            setTimeout(function() {
                document.getElementById('template-content').innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-3">
                                <div class="text-2xl">📦</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-2">기본 API 템플릿</h4>
                                    <p class="text-sm text-gray-600 mb-3">CRUD 작업을 위한 기본 API 함수 템플릿</p>
                                    <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded mb-3">API</span>
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">CRUD</span>
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Database</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('sandbox.function-creator') }}" class="flex-1 px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-center">
                                            사용하기
                                        </a>
                                        <button class="px-3 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                            미리보기
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-3">
                                <div class="text-2xl">🔄</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-2">데이터 처리 템플릿</h4>
                                    <p class="text-sm text-gray-600 mb-3">대용량 데이터 배치 처리를 위한 템플릿</p>
                                    <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded mb-3">Data</span>
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Batch</span>
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Processing</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('sandbox.function-creator') }}" class="flex-1 px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-center">
                                            사용하기
                                        </a>
                                        <button class="px-3 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                            미리보기
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-3">
                                <div class="text-2xl">📧</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-2">이메일 알림 템플릿</h4>
                                    <p class="text-sm text-gray-600 mb-3">이메일 발송 및 알림을 위한 템플릿</p>
                                    <span class="inline-block px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded mb-3">Notification</span>
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Email</span>
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Queue</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('sandbox.function-creator') }}" class="flex-1 px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-center">
                                            사용하기
                                        </a>
                                        <button class="px-3 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                            미리보기
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }, 1500);
        });
    </script>
</body>
</html>