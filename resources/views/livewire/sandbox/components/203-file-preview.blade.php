<!-- 미리보기 영역 -->
<div>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">미리보기</label>
        <div class="w-full border border-gray-300 rounded-md bg-gray-50 h-96 overflow-auto">
            @if($fileName && $content)
                @php
                    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $isBladeFile = str_ends_with($fileName, '.blade.php');
                @endphp
                
                @if($isBladeFile || $extension === 'php')
                    <!-- Blade/PHP 파일 미리보기 -->
                    <div class="p-4">
                        @if($isBladeFile)
                            <div class="mb-2 text-xs text-gray-500">Blade 템플릿 렌더링:</div>
                            <div class="border rounded p-3 bg-white">
                                {!! $this->renderBladePreview() !!}
                            </div>
                        @else
                            <div class="mb-2 text-xs text-gray-500">PHP 코드:</div>
                            <pre class="font-mono text-sm bg-gray-800 text-green-400 p-4 rounded overflow-auto"><code>{{ $content }}</code></pre>
                        @endif
                    </div>
                @elseif(in_array($extension, ['css', 'scss']))
                    <!-- CSS 미리보기 -->
                    <div class="p-4">
                        <div class="mb-2 text-xs text-gray-500">CSS 스타일:</div>
                        <pre class="font-mono text-sm bg-gray-800 text-blue-400 p-4 rounded overflow-auto"><code>{{ $content }}</code></pre>
                    </div>
                @elseif(in_array($extension, ['js', 'ts']))
                    <!-- JavaScript 미리보기 -->
                    <div class="p-4">
                        <div class="mb-2 text-xs text-gray-500">JavaScript 코드:</div>
                        <pre class="font-mono text-sm bg-gray-800 text-yellow-400 p-4 rounded overflow-auto"><code>{{ $content }}</code></pre>
                    </div>
                @elseif(in_array($extension, ['md', 'txt']))
                    <!-- 텍스트/마크다운 미리보기 -->
                    <div class="p-4">
                        <div class="mb-2 text-xs text-gray-500">텍스트 내용:</div>
                        <div class="bg-white p-4 rounded border whitespace-pre-wrap">{{ $content }}</div>
                    </div>
                @else
                    <!-- 기본 텍스트 미리보기 -->
                    <div class="p-4">
                        <div class="mb-2 text-xs text-gray-500">파일 내용:</div>
                        <pre class="font-mono text-sm bg-white p-4 rounded border overflow-auto"><code>{{ $content }}</code></pre>
                    </div>
                @endif
            @else
                <div class="p-8 text-center text-gray-500">
                    파일을 선택하고 내용을 입력하면 미리보기가 표시됩니다.
                </div>
            @endif
        </div>
    </div>
</div>