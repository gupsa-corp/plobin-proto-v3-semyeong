@foreach($items as $item)
    <div class="mb-1">
        @if($item['type'] === 'folder')
            {{-- 폴더 아이템 --}}
            <div class="group">
                <button 
                    wire:click="toggleFolder('{{ $item['relativePath'] }}')"
                    class="flex items-center w-full px-2 py-1 text-sm hover:bg-gray-200 rounded"
                    style="padding-left: {{ ($level * 12) + 8 }}px"
                >
                    <span class="mr-1 transition-transform {{ $item['expanded'] ? 'rotate-90' : '' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                    <span class="mr-2">📁</span>
                    <span class="truncate">{{ $item['name'] }}</span>
                </button>
                
                {{-- 하위 아이템들 --}}
                @if($item['expanded'] && !empty($item['children']))
                    @include('700-page-sandbox.704-page-file-editor.200-file-tree-item', ['items' => $item['children'], 'level' => $level + 1])
                @endif
            </div>
        @else
            {{-- 파일 아이템 --}}
            <button 
                wire:click="openFile('{{ $item['path'] }}')"
                class="flex items-center w-full px-2 py-1 text-sm hover:bg-gray-200 rounded group {{ in_array($item['relativePath'], $openTabs) ? 'bg-blue-50 text-blue-700' : '' }}"
                style="padding-left: {{ ($level * 12) + 20 }}px"
            >
                <span class="mr-2 flex-shrink-0">
                    @php
                        $icon = match($item['extension']) {
                            'html' => '🌐',
                            'css' => '🎨',
                            'js' => '⚡',
                            'php' => '🔥',
                            'json' => '📊',
                            'md' => '📝',
                            'txt' => '📄',
                            'png', 'jpg', 'jpeg', 'gif', 'svg' => '🖼️',
                            default => '📄'
                        };
                    @endphp
                    {{ $icon }}
                </span>
                <span class="truncate">{{ $item['name'] }}</span>
            </button>
        @endif
    </div>
@endforeach