<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- 왼쪽 컬럼: 설정 및 템플릿 -->
    <div class="xl:col-span-1 space-y-6">
        <!-- 템플릿 선택 섹션 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">API 템플릿</h2>
        
        <div class="space-y-4">
            <!-- 카테고리별 템플릿 선택 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">카테고리별 템플릿</label>
                
                @php
                    $categories = [
                        'basic' => ['name' => '기본', 'color' => 'blue'],
                        'crud' => ['name' => 'CRUD', 'color' => 'green'],
                        'auth' => ['name' => '인증', 'color' => 'purple'],
                        'file' => ['name' => '파일', 'color' => 'yellow'],
                        'advanced' => ['name' => '고급', 'color' => 'red']
                    ];
                    
                    $templatesByCategory = collect($templates)->groupBy('category');
                @endphp
                
                @foreach($categories as $categoryKey => $category)
                    @if(isset($templatesByCategory[$categoryKey]))
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-600 mb-2 flex items-center">
                                <span class="w-2 h-2 bg-{{ $category['color'] }}-500 rounded-full mr-2"></span>
                                {{ $category['name'] }} 템플릿
                            </h3>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach($templatesByCategory[$categoryKey] as $key => $template)
                                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 cursor-pointer transition-colors
                                              {{ $selectedTemplate === $key ? 'bg-'.$category['color'].'-50 border-'.$category['color'].'-300' : '' }}"
                                         wire:click="$set('selectedTemplate', '{{ $key }}')">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900 text-sm">{{ $template['name'] }}</h4>
                                                <p class="text-xs text-gray-500 mt-1">{{ $template['description'] }}</p>
                                                <span class="inline-block px-2 py-1 text-xs font-medium bg-{{ $category['color'] }}-100 
                                                           text-{{ $category['color'] }}-800 rounded-full mt-2">
                                                    {{ $template['method'] }}
                                                </span>
                                            </div>
                                            @if($selectedTemplate === $key)
                                                <i class="fas fa-check-circle text-{{ $category['color'] }}-600 ml-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            
            <button wire:click="loadTemplate" 
                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ $selectedTemplate ? '' : 'disabled' }}>
                <i class="fas fa-download mr-2"></i>선택된 템플릿 로드
            </button>
        </div>
    </div>

        <!-- API 정보 입력 섹션 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">API 정보</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">API 이름</label>
                <input type="text" wire:model="apiName" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('apiName') border-red-500 @enderror"
                       placeholder="예: User, Product, Order">
                @error('apiName')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">HTTP 메서드</label>
                <select wire:model="httpMethod" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="DELETE">DELETE</option>
                    <option value="RESOURCE">RESOURCE (전체 CRUD)</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">API 라우트</label>
                <input type="text" wire:model="apiRoute" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="api/users">
                <p class="mt-1 text-xs text-gray-500">예: api/users, api/products, api/orders</p>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">API 설명</label>
                <textarea wire:model="apiDescription" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('apiDescription') border-red-500 @enderror"
                          rows="3"
                          placeholder="API의 기능과 목적을 설명하세요"></textarea>
                @error('apiDescription')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" wire:model="generateController" class="rounded border-gray-300">
                    <span class="text-sm text-gray-700">실제 컨트롤러 파일 생성 (app/Http/Controllers/Api/)</span>
                </label>
            </div>
        </div>
        </div>

        <!-- 액션 버튼 섹션 -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">API 작업</h2>
            
            <div class="space-y-3">
                <button wire:click="testApi" 
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
                    <i class="fas fa-check-circle mr-2"></i>문법 검사
                </button>
                
                <button wire:click="saveApi" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-save mr-2"></i>API 저장
                </button>
                
                <button wire:click="generateApiDoc" 
                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200">
                    <i class="fas fa-file-text mr-2"></i>문서 생성
                </button>
            </div>
            
            <div class="mt-4 p-4 bg-gray-50 rounded-md">
                <h3 class="text-sm font-medium text-gray-700 mb-2">미리보기</h3>
                @if($apiName && $apiRoute)
                    <p class="text-sm text-gray-600">
                        <strong>컨트롤러:</strong> {{ ucfirst(str_replace(' ', '', $apiName)) }}Controller<br>
                        <strong>라우트:</strong> {{ $httpMethod }} /{{ $apiRoute }}<br>
                        @if($generateController)
                            <span class="text-green-600"><strong>✓ 실제 컨트롤러 파일이 생성됩니다</strong></span>
                        @else
                            <span class="text-gray-500">샌드박스에만 저장됩니다</span>
                        @endif
                    </p>
                @else
                    <p class="text-sm text-gray-500">API 이름과 라우트를 입력하면 미리보기가 표시됩니다.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- 오른쪽 컬럼: 코드 에디터 및 실시간 미리보기 -->
    <div class="xl:col-span-2 space-y-6">
        <!-- API 코드 입력 섹션 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">API 코드</h2>
        
            <div class="flex justify-between items-center mb-4">
                <label class="block text-sm font-medium text-gray-700">PHP 코드</label>
                <div class="flex space-x-2">
                    <button type="button" onclick="formatCode()" class="text-sm text-indigo-600 hover:text-indigo-700">
                        <i class="fas fa-magic mr-1"></i>코드 포맷
                    </button>
                    <button type="button" onclick="copyCode()" class="text-sm text-gray-600 hover:text-gray-700">
                        <i class="fas fa-copy mr-1"></i>복사
                    </button>
                </div>
            </div>
            
            <div class="relative">
                <div id="editor" class="w-full border border-gray-300 rounded-md" style="height: 500px;"></div>
                <textarea wire:model="apiCode" id="hidden-textarea" class="hidden" @error('apiCode') border-red-500 @enderror></textarea>
                @error('apiCode')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- 실시간 미리보기 섹션 -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900">실시간 미리보기</h2>
                <button wire:click="refreshPreview" class="text-sm text-indigo-600 hover:text-indigo-700">
                    <i class="fas fa-sync-alt mr-1"></i>새로고침
                </button>
            </div>
            
            <div class="bg-gray-900 rounded-md overflow-hidden">
                <div class="bg-gray-800 px-4 py-2 flex justify-between items-center">
                    <span class="text-gray-300 text-sm">Generated API Response</span>
                    <span class="text-xs text-gray-400">{{ $httpMethod }} /{{ $apiRoute ?: 'api/example' }}</span>
                </div>
                <pre class="p-4 text-sm text-green-400 overflow-x-auto"><code id="preview-content">{{
    json_encode([
        "message" => "Success",
        "data" => [],
        "timestamp" => now()->toISOString(),
        "controller" => ucfirst(str_replace(' ', '', $apiName ?: 'Example')) . 'Controller'
    ], JSON_PRETTY_PRINT)
}}</code></pre>
            </div>
        </div>
    </div>

    <!-- 결과 메시지 -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="xl:col-span-3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif
</div>

<!-- Monaco Editor Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>
<script>
    let editor;
    
    document.addEventListener('DOMContentLoaded', function() {
        require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' }});
        
        require(['vs/editor/editor.main'], function() {
            editor = monaco.editor.create(document.getElementById('editor'), {
                value: document.getElementById('hidden-textarea').value || '<?php\n\n// API 코드를 작성하세요',
                language: 'php',
                theme: 'vs-dark',
                automaticLayout: true,
                fontSize: 14,
                minimap: { enabled: false },
                scrollBeyondLastLine: false,
                wordWrap: 'on',
                formatOnType: true,
                formatOnPaste: true
            });
            
            // Sync with Livewire
            editor.onDidChangeModelContent(function() {
                document.getElementById('hidden-textarea').value = editor.getValue();
                @this.set('apiCode', editor.getValue());
            });
            
            // Listen for Livewire updates
            window.addEventListener('template-loaded', function() {
                editor.setValue(@this.get('apiCode') || '<?php\n\n// API 코드를 작성하세요');
            });
        });
    });
    
    function formatCode() {
        if (editor) {
            editor.getAction('editor.action.formatDocument').run();
        }
    }
    
    function copyCode() {
        if (editor) {
            navigator.clipboard.writeText(editor.getValue()).then(function() {
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                toast.textContent = '코드가 클립보드에 복사되었습니다';
                document.body.appendChild(toast);
                setTimeout(() => document.body.removeChild(toast), 3000);
            });
        }
    }
</script>