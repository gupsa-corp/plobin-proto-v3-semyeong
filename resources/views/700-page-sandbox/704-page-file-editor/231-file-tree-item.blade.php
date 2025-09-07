@foreach($items as $item)
    <div class="file-tree-item" style="margin-left: {{ $level * 16 }}px">
        @if($item['type'] === 'folder')
            {{-- 폴더 --}}
            <div class="flex items-center group py-1 px-2 hover:bg-gray-100 rounded cursor-pointer">
                <button
                    wire:click="toggleFolder('{{ $item['path'] }}')"
                    class="flex items-center flex-1 min-w-0"
                >
                    <svg class="w-4 h-4 mr-2 text-gray-500 transform transition-transform {{ $item['expanded'] ? 'rotate-90' : '' }}" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <div class="flex items-center min-w-0">
                        <span class="mr-2">📁</span>
                        <span class="text-sm text-gray-800 truncate">{{ $item['name'] }}</span>
                    </div>
                </button>
                
                {{-- 폴더 컨텍스트 메뉴 --}}
                <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center space-x-1">
                    <button
                        onclick="createNewFile('{{ $item['relativePath'] }}')"
                        class="p-1 text-gray-400 hover:text-gray-600"
                        title="새 파일"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                    <button
                        onclick="renameItem('{{ $item['path'] }}')"
                        class="p-1 text-gray-400 hover:text-gray-600"
                        title="이름 변경"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </button>
                    <button
                        onclick="deleteItem('{{ $item['path'] }}', true)"
                        class="p-1 text-gray-400 hover:text-red-600"
                        title="폴더 삭제"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            {{-- 하위 항목 --}}
            @if($item['expanded'] && !empty($item['children']))
                @include('700-page-sandbox.704-page-file-editor.231-file-tree-item', ['items' => $item['children'], 'level' => $level + 1])
            @endif
        @else
            {{-- 파일 --}}
            <div class="flex items-center group py-1 px-2 hover:bg-gray-100 rounded cursor-pointer {{ $selectedFile === $item['path'] ? 'bg-blue-50 border-l-2 border-blue-500' : '' }}">
                <button
                    wire:click="openFile('{{ $item['path'] }}')"
                    class="flex items-center flex-1 min-w-0"
                >
                    <div class="w-6"></div> {{-- 폴더 화살표 공간 --}}
                    <div class="flex items-center min-w-0">
                        <span class="mr-2">
                            @php
                                $ext = $item['extension'] ?? '';
                                $icon = match($ext) {
                                    'html' => '🌐',
                                    'css' => '🎨',
                                    'js' => '⚡',
                                    'php' => '🔥',
                                    'json' => '📊',
                                    'md' => '📝',
                                    'txt' => '📄',
                                    default => '📄'
                                };
                            @endphp
                            {{ $icon }}
                        </span>
                        <span class="text-sm text-gray-800 truncate">{{ $item['name'] }}</span>
                    </div>
                </button>
                
                {{-- 파일 메타데이터 --}}
                <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center space-x-1 text-xs text-gray-400">
                    <span>{{ number_format($item['size'] ?? 0) }}B</span>
                </div>
                
                {{-- 파일 컨텍스트 메뉴 --}}
                <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center space-x-1 ml-2">
                    <button
                        onclick="renameItem('{{ $item['path'] }}')"
                        class="p-1 text-gray-400 hover:text-gray-600"
                        title="이름 변경"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </button>
                    <button
                        onclick="deleteItem('{{ $item['path'] }}')"
                        class="p-1 text-gray-400 hover:text-red-600"
                        title="파일 삭제"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
@endforeach