@extends('700-page-sandbox.700-common.700-common-sandbox')

@section('title', '코드 실행')

@section('content')
<div class="space-y-6" x-data="{
    executeType: 'view',
    executePath: '',
    executeResult: {}
}">
    <!-- 알림 메시지 -->
    <div id="message-area">
        <!-- 구현필요 -->
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">실행 유형</label>
        <select x-model="executeType"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="view">View 렌더링</option>
            <option value="controller">Controller 실행</option>
            <option value="livewire">Livewire 컴포넌트</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">실행 경로/이름</label>
        <input x-model="executePath"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="예: test-view, TestController@index, TestComponent">
    </div>

    <button @click="executeCode"
            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
        실행
    </button>

    <!-- 실행 결과 -->
    <template x-if="Object.keys(executeResult).length > 0">
        <div class="bg-gray-50 p-4 rounded">
            <h3 class="font-medium text-gray-900 mb-3">실행 결과</h3>
            <pre class="text-sm text-gray-800 whitespace-pre-wrap" x-text="JSON.stringify(executeResult, null, 2)"></pre>
        </div>
    </template>
</div>

<script>
function executeCode() {
    // 구현필요
}
</script>
@endsection
