<div class="space-y-6">
    <!-- API 정보 입력 섹션 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">API 정보</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">API 이름</label>
                <input type="text" wire:model="apiName" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('apiName') border-red-500 @enderror"
                       placeholder="예: UserController">
                @error('apiName')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">API 설명</label>
                <textarea wire:model="apiDescription" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('apiDescription') border-red-500 @enderror"
                          rows="3"
                          placeholder="API의 기능과 목적을 설명하세요"></textarea>
                @error('apiDescription')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
    <div class="flex space-x-4">
        <button wire:click="saveApi" 
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            API 저장
        </button>
        
        <button wire:click="testApi" 
                class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
            API 테스트
        </button>
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