<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Form Preview</h2>
        <p class="text-sm text-gray-600">생성된 폼을 미리 보고 테스트하세요</p>
    </div>
    
    <div class="p-6">
        <div id="formio-preview" class="min-h-48 border border-gray-200 rounded-lg p-4">
            <p class="text-gray-500 text-center">폼을 생성하면 여기에 미리보기가 표시됩니다</p>
        </div>
        
        <!-- Form Data Display -->
        <div class="mt-6">
            <h3 class="text-sm font-medium text-gray-700 mb-3">폼 데이터 (제출 시 표시)</h3>
            <pre id="form-data" class="bg-gray-100 p-4 rounded-md text-xs overflow-auto max-h-48">
{
    "message": "폼을 제출하면 데이터가 여기에 표시됩니다"
}
            </pre>
        </div>
    </div>
</div>