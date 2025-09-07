<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">데이터베이스 테이블</h3>
            <div class="text-sm text-gray-500">
                선택된 샌드박스: <span class="font-medium">storage-sandbox-{{ $selectedSandbox }}</span>
            </div>
        </div>
        
        @if(empty($tables))
            <p class="text-gray-500">테이블이 없습니다.</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                @foreach($tables as $table)
                    <button 
                        wire:click="selectTable('{{ $table }}')"
                        class="text-left px-3 py-2 rounded-lg border hover:bg-blue-50 hover:border-blue-300 transition-colors
                               {{ $selectedTable === $table ? 'bg-blue-100 border-blue-500 text-blue-700' : 'border-gray-200 text-gray-700' }}"
                    >
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 1.79 4 4 4h8c0-2.21-1.79-4-4-4H4V7z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7c0-2.21 1.79-4 4-4h8c2.21 0 4 1.79 4 4v10c0 2.21-1.79 4-4 4"></path>
                            </svg>
                            {{ $table }}
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    @if($selectedTable && !empty($columns))
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $selectedTable }} 테이블 데이터
                </h3>
                <div class="flex items-center space-x-4">
                    <select wire:model="perPage" class="border rounded px-3 py-1 text-sm">
                        <option value="10">10개씩</option>
                        <option value="25">25개씩</option>
                        <option value="50">50개씩</option>
                        <option value="100">100개씩</option>
                    </select>
                </div>
            </div>
            
            <!-- 검색 -->
            <div class="mb-4">
                <input 
                    type="text" 
                    wire:model.debounce.300ms="search" 
                    placeholder="전체 컬럼에서 검색..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            @if($paginatedData && $paginatedData->count() > 0)
                <!-- 테이블 -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach($columns as $column)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <button 
                                            wire:click="sortBy('{{ $column }}')" 
                                            class="flex items-center space-x-1 hover:text-gray-700"
                                        >
                                            <span>{{ $column }}</span>
                                            @if($sortField === $column)
                                                @if($sortDirection === 'asc')
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M5 8l5-5 5 5H5z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M15 12l-5 5-5-5h10z"/>
                                                    </svg>
                                                @endif
                                            @endif
                                        </button>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($paginatedData as $row)
                                <tr class="hover:bg-gray-50">
                                    @foreach($columns as $column)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @php
                                                $value = $row->{$column} ?? '';
                                                $displayValue = is_string($value) && strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
                                            @endphp
                                            <span title="{{ $value }}">{{ $displayValue }}</span>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- 페이지네이션 -->
                <div class="mt-4">
                    {{ $paginatedData->links() }}
                </div>
            @else
                <p class="text-gray-500 text-center py-8">데이터가 없습니다.</p>
            @endif
        </div>
    @elseif($selectedTable)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <p class="text-gray-500 text-center py-8">컬럼 정보를 가져올 수 없습니다.</p>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif
</div>