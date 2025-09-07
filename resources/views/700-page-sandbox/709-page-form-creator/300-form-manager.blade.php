<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Form Manager</h2>
        <p class="text-sm text-gray-600">저장된 폼을 관리하세요</p>
    </div>
    
    <div class="p-4">
        <!-- Save Form Section -->
        <div class="mb-6">
            <label for="form-name" class="block text-sm font-medium text-gray-700 mb-2">폼 이름</label>
            <input type="text" id="form-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="폼 이름을 입력하세요">
        </div>
        
        <!-- Form List -->
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-700 mb-3">저장된 폼</h3>
            <div id="form-list" class="space-y-2">
                <!-- 저장된 폼 목록이 여기에 표시됩니다 -->
            </div>
        </div>
        
        <!-- JSON Export/Import -->
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">JSON Export</label>
                <textarea id="json-export" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs" rows="8" readonly placeholder="폼 JSON이 여기에 표시됩니다"></textarea>
                <button id="copy-json" class="mt-2 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                    JSON 복사
                </button>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">JSON Import</label>
                <textarea id="json-import" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs" rows="6" placeholder="JSON을 붙여넣으세요"></textarea>
                <button id="load-json" class="mt-2 px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                    JSON 불러오기
                </button>
            </div>
        </div>
    </div>
</div>