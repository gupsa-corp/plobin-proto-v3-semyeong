@extends('700-page-sandbox.700-common.700-common-sandbox')

@section('title', 'SQL 실행')

@section('content')
<div class="space-y-6" x-data="{
    sqlQuery: 'SELECT * FROM test_table;',
    sqlResult: [],
    sqlHistory: []
}">
                <!-- 알림 메시지 -->
                <div id="message-area">
                    <!-- 구현필요 -->
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <!-- SQL 쿼리 입력 -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">SQL 쿼리</label>
                        <textarea x-model="sqlQuery"
                                  rows="8"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                  placeholder="SELECT * FROM test_table;"></textarea>

                        <button @click="executeSql"
                                class="mt-3 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            실행
                        </button>
                    </div>

                    <!-- 쿼리 히스토리 -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">쿼리 히스토리</h3>
                        <div class="bg-gray-50 rounded-md p-3 max-h-64 overflow-y-auto">
                            <template x-if="sqlHistory.length > 0">
                                <div>
                                    <template x-for="(history, index) in sqlHistory" :key="index">
                                        <div class="mb-2 p-2 bg-white rounded border text-xs">
                                            <div class="text-gray-500 mb-1" x-text="history.timestamp"></div>
                                            <div class="font-mono cursor-pointer hover:bg-gray-100 p-1 rounded"
                                                 :class="history.error ? 'text-red-600' : 'text-gray-900'"
                                                 @click="executeHistoryQuery(index)"
                                                 title="클릭하여 다시 실행"
                                                 x-text="history.query.length > 50 ? history.query.substring(0, 50) + '...' : history.query">
                                            </div>
                                            <template x-if="history.error">
                                                <div class="text-red-500 text-xs mt-1">
                                                    오류: <span x-text="history.error.length > 30 ? history.error.substring(0, 30) + '...' : history.error"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="sqlHistory.length === 0">
                                <div class="text-gray-500 text-sm">실행된 쿼리가 없습니다.</div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- 실행 결과 -->
                <template x-if="sqlResult.length > 0">
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="font-medium text-gray-900 mb-3">실행 결과</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <template x-if="sqlResult.length > 0">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <template x-for="key in Object.keys(sqlResult[0])" :key="key">
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase" x-text="key"></th>
                                            </template>
                                        </tr>
                                    </thead>
                                </template>
                                <tbody>
                                    <template x-for="row in sqlResult" :key="row">
                                        <tr class="border-b">
                                            <template x-for="value in Object.values(row)" :key="value">
                                                <td class="px-4 py-2 text-sm text-gray-900" x-text="value"></td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
            </div>


<script>
function executeSql() {
    // 구현필요
}

function executeHistoryQuery(index) {
    // 구현필요
}
</script>
@endsection