<div class="space-y-6">
    <!-- 템플릿 선택 섹션 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">API 템플릿</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">템플릿 선택</label>
                <select wire:model="selectedTemplate" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- 템플릿을 선택하세요 --</option>
                    @foreach($templates as $key => $template)
                        <option value="{{ $key }}">{{ $template['name'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <button wire:click="loadTemplate" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                템플릿 로드
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

    <!-- API 코드 입력 섹션 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">API 코드</h2>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">PHP 코드</label>
            <textarea wire:model="apiCode" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm @error('apiCode') border-red-500 @enderror"
                      rows="15"
                      placeholder="PHP 코드를 입력하세요..."></textarea>
            @error('apiCode')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- 액션 버튼 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">API 작업</h2>
        
        <div class="flex flex-wrap gap-4">
            <button wire:click="testApi" 
                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
                <i class="fas fa-check-circle mr-2"></i>문법 검사
            </button>
            
            <button wire:click="saveApi" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                <i class="fas fa-save mr-2"></i>API 저장
            </button>
            
            <button wire:click="generateApiDoc" 
                    class="px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-200">
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

    <!-- 결과 메시지 -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif
</div>