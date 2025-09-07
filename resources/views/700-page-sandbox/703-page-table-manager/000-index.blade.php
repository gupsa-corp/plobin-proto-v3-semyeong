@extends('700-page-sandbox.700-common.700-common-sandbox')

@section('title', '테이블 관리')

@section('content')
<div class="space-y-6" x-data="{
    tableList: [],
    selectedTable: '',
    tableSchema: []
}">
                <!-- 알림 메시지 -->
                <div id="message-area">
                    <!-- 구현필요 -->
                </div>

                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">테이블 관리</h2>
                    <button @click="loadTables"
                            class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        새로고침
                    </button>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <!-- 테이블 목록 -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3">테이블 목록</h3>
                        <div class="bg-gray-50 rounded-md p-3">
                            <template x-if="tableList.length > 0">
                                <div>
                                    <template x-for="table in tableList" :key="table">
                                        <button @click="selectTable(table)"
                                                class="block w-full text-left px-3 py-2 text-sm rounded hover:bg-white mb-1"
                                                :class="selectedTable === table ? 'bg-blue-100 text-blue-700' : 'text-gray-700'">
                                            📊 <span x-text="table"></span>
                                        </button>
                                    </template>
                                </div>
                            </template>
                            <template x-if="tableList.length === 0">
                                <div class="text-gray-500 text-sm">테이블이 없습니다.</div>
                            </template>
                        </div>
                    </div>

                    <!-- 테이블 스키마 -->
                    <div class="col-span-2">
                        <template x-if="selectedTable">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-3">
                                    <span x-text="selectedTable"></span> 테이블 스키마
                                </h3>

                                <template x-if="tableSchema.length > 0">
                                    <div>
                                        <div class="bg-white border rounded-lg overflow-hidden">
                                            <table class="min-w-full">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">컬럼명</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">타입</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">NOT NULL</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">기본값</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">기본키</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    <template x-for="column in tableSchema" :key="column.name">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm font-medium text-gray-900" x-text="column.name"></td>
                                                            <td class="px-4 py-2 text-sm text-gray-700" x-text="column.type"></td>
                                                            <td class="px-4 py-2 text-sm text-gray-700" x-text="column.notnull ? 'YES' : 'NO'"></td>
                                                            <td class="px-4 py-2 text-sm text-gray-700" x-text="column.dflt_value || 'NULL'"></td>
                                                            <td class="px-4 py-2 text-sm text-gray-700" x-text="column.pk ? 'YES' : 'NO'"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- 빠른 쿼리 버튼들 -->
                                        <div class="mt-4 flex space-x-2">
                                            <button @click="quickDataQuery"
                                                    class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                                                데이터 조회
                                            </button>
                                            <button @click="quickCountQuery"
                                                    class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                                                개수 조회
                                            </button>
                                            <button @click="quickSchemaQuery"
                                                    class="px-3 py-1 bg-purple-600 text-white rounded text-sm hover:bg-purple-700">
                                                스키마 조회
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="tableSchema.length === 0">
                                    <div class="text-gray-500">테이블 스키마를 가져오는 중...</div>
                                </template>
                            </div>
                        </template>

                        <template x-if="!selectedTable">
                            <div class="text-center text-gray-500 py-8">
                                <p>테이블을 선택하면 스키마 정보가 표시됩니다.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

<script>
function loadTables() {
    // 구현필요
}

function selectTable(tableName) {
    // 구현필요
}

function quickDataQuery() {
    // 구현필요
}

function quickCountQuery() {
    // 구현필요
}

function quickSchemaQuery() {
    // 구현필요
}
</script>
@endsection