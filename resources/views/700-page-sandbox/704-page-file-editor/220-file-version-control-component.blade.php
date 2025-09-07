<div class="file-version-control">
    {{-- 헤더 --}}
    <div class="p-3 border-b border-gray-200 bg-gray-100">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-700">버전 관리</h3>
            @if($currentFile)
                <button 
                    wire:click="openVersionDialog"
                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors"
                >
                    버전 저장
                </button>
            @endif
        </div>
        @if($currentFile)
            <div class="text-xs text-gray-500 mt-1">
                {{ $currentFile }}
            </div>
        @endif
    </div>

    {{-- 버전 목록 --}}
    <div class="flex-1 overflow-auto">
        @if(!$currentFile)
            <div class="p-4 text-center text-gray-500 text-sm">
                파일을 선택해주세요
            </div>
        @elseif(empty($fileVersions))
            <div class="p-4 text-center text-gray-500 text-sm">
                저장된 버전이 없습니다
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($fileVersions as $version)
                    <div class="p-3 hover:bg-gray-50 group">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                {{-- 커밋 메시지 --}}
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $version['message'] }}
                                </div>
                                
                                {{-- 메타데이터 --}}
                                <div class="text-xs text-gray-500 mt-1 space-y-1">
                                    <div>{{ \Carbon\Carbon::parse($version['created_at'])->format('Y-m-d H:i:s') }}</div>
                                    <div class="flex items-center space-x-3">
                                        <span>{{ number_format($version['size']) }} bytes</span>
                                        <span class="font-mono">{{ substr($version['hash'], 0, 8) }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- 액션 버튼들 --}}
                            <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button
                                    wire:click="restoreVersion('{{ $version['id'] }}')"
                                    class="p-1 text-green-600 hover:text-green-800 transition-colors"
                                    title="이 버전으로 복원"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                                
                                <button
                                    onclick="showVersionPreview('{{ $version['id'] }}')"
                                    class="p-1 text-blue-600 hover:text-blue-800 transition-colors"
                                    title="미리보기"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                
                                <button
                                    wire:click="deleteVersion('{{ $version['id'] }}')"
                                    wire:confirm="이 버전을 삭제하시겠습니까?"
                                    class="p-1 text-red-600 hover:text-red-800 transition-colors"
                                    title="버전 삭제"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 버전 저장 다이얼로그 --}}
    @if($showVersionDialog)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">새 버전 저장</h3>
                </div>
                
                <div class="p-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            커밋 메시지
                        </label>
                        <textarea
                            wire:model="commitMessage"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="이 버전의 변경사항을 설명해주세요..."
                        ></textarea>
                    </div>
                </div>
                
                <div class="p-4 border-t border-gray-200 flex justify-end space-x-2">
                    <button
                        wire:click="closeVersionDialog"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors"
                    >
                        취소
                    </button>
                    <button
                        wire:click="saveVersion"
                        :disabled="!commitMessage.trim()"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed rounded-md transition-colors"
                    >
                        저장
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function showVersionPreview(versionId) {
    // 버전 미리보기 모달 구현
    alert('버전 미리보기 기능: ' + versionId);
}
</script>