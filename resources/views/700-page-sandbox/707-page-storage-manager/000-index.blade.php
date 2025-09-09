<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '스토리지 관리자'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen sandbox-container">
        <!-- 메시지 알림 -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 fade-in">
            <strong class="font-bold">성공!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 fade-in">
            <strong class="font-bold">오류!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 fade-in">
            <strong class="font-bold">오류가 발생했습니다:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="sandbox-card">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">샌드박스 스토리지 관리</h1>
            <p class="text-gray-600 mb-8">템플릿을 기반으로 새로운 샌드박스 스토리지를 생성하고 관리합니다.</p>
            
            <!-- 새 스토리지 생성 -->
            <div class="sandbox-card bg-blue-50 border border-blue-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">새 샌드박스 스토리지 생성</h2>
                
                <form method="POST" action="{{ route('sandbox.storage.create') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label for="storage_name" class="block text-sm font-medium text-gray-700 mb-1">
                                스토리지 이름
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">
                                    sandbox/
                                </span>
                                <input type="text" 
                                       class="sandbox-input rounded-l-none @error('storage_name') border-red-500 @enderror" 
                                       id="storage_name" 
                                       name="storage_name" 
                                       value="{{ old('storage_name') }}"
                                       placeholder="스토리지 이름 입력">
                            </div>
                            @error('storage_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">영문자, 숫자, 하이픈(-), 언더스코어(_)만 사용 가능</p>
                        </div>
                        <div>
                            <label for="template_name" class="block text-sm font-medium text-gray-700 mb-1">
                                템플릿 선택
                            </label>
                            <select name="template_name" 
                                    id="template_name"
                                    class="sandbox-input @error('template_name') border-red-500 @enderror">
                                @forelse($templates as $template)
                                    <option value="{{ $template['name'] }}" 
                                            {{ old('template_name', 'default') == $template['name'] ? 'selected' : '' }}>
                                        {{ $template['display_name'] }} ({{ $template['file_count'] }}개 파일, {{ $template['size'] }})
                                    </option>
                                @empty
                                    <option value="">사용 가능한 템플릿 없음</option>
                                @endforelse
                            </select>
                            @error('template_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="sandbox-button w-full">
                                ✨ 스토리지 생성
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 기존 스토리지 목록 -->
            <div class="sandbox-card">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">기존 샌드박스 스토리지 목록</h2>
                <!-- 디버깅 정보 -->
                <div class="mb-4 p-2 bg-yellow-100 text-xs">
                    <strong>디버깅:</strong> 스토리지 개수: {{ count($storages ?? []) }}, 현재 선택: {{ $currentStorage ?? 'null' }}
                    @if(!empty($storages))
                        <br>스토리지 목록: 
                        @foreach($storages as $storage)
                            {{ $storage['name'] ?? 'unknown' }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    @endif
                    @if(isset($debugInfo))
                        <br><strong>경로정보:</strong>
                        <br>• Storage Path: {{ $debugInfo['storage_path'] ?? 'N/A' }}
                        <br>• CWD: {{ $debugInfo['cwd'] ?? 'N/A' }}
                        <br>• Storage Exists: {{ $debugInfo['storage_exists'] ? 'YES' : 'NO' }}
                        <br>• Directories: {{ count($debugInfo['directories'] ?? []) }}개
                        @if(!empty($debugInfo['directories']))
                            <br>&nbsp;&nbsp;{{ implode(', ', array_map('basename', $debugInfo['directories'])) }}
                        @endif
                    @endif
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">스토리지 이름</th>
                                <th class="px-6 py-3">생성일</th>
                                <th class="px-6 py-3">크기</th>
                                <th class="px-6 py-3">파일 수</th>
                                <th class="px-6 py-3">작업</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($storages as $storage)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <div class="flex items-center">
                                        <strong>{{ $storage['name'] }}</strong>
                                        @if($storage['name'] === $currentStorage)
                                            <span class="ml-2 px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                현재 선택됨
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $storage['created_at'] }}</td>
                                <td class="px-6 py-4">{{ $storage['size'] }}</td>
                                <td class="px-6 py-4">{{ $storage['file_count'] }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        @if($storage['name'] !== $currentStorage)
                                            <form method="POST" action="{{ route('sandbox.storage.select') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="storage_name" value="{{ $storage['name'] }}">
                                                <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    선택
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($storage['name'] !== 'template')
                                            <form method="POST" action="{{ route('sandbox.storage.delete') }}" class="inline" 
                                                  onsubmit="return confirm('정말로 {{ $storage['name'] }} 스토리지를 삭제하시겠습니까?')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="storage_name" value="{{ $storage['name'] }}">
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    삭제
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white border-b">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    등록된 스토리지가 없습니다. 새로운 스토리지를 생성해보세요.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 도움말 -->
            <div class="sandbox-card bg-gray-50 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">💡 사용 안내</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p>• 새 스토리지는 선택한 템플릿을 복사하여 생성됩니다.</p>
                    <p>• 스토리지를 선택하면 모든 샌드박스 기능이 해당 스토리지를 기준으로 동작합니다.</p>
                    <p>• 템플릿 스토리지는 삭제할 수 없습니다.</p>
                    <p>• 현재 선택된 스토리지를 삭제할 경우 기본 스토리지(1)로 자동 전환됩니다.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // 메시지 자동 숨김 (5초 후)
        setTimeout(function() {
            const alerts = document.querySelectorAll('.fade-in');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    </script>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Filament Scripts -->
    @filamentScripts
</body>
</html>