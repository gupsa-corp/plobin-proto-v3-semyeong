<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- 메인 타이틀 및 스토리지 선택 -->
        <div class="flex items-center justify-between h-16">
            <a href="/sandbox" class="text-xl font-bold text-gray-900 hover:text-gray-700">
                샌드박스
            </a>

            <!-- 스토리지 선택 드롭다운 -->
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <label for="storage-select" class="text-sm text-gray-600 mr-2">선택된 샌드박스:</label>
                    <select id="storage-select"
                            class="text-sm border border-gray-300 rounded-md px-3 py-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="selectStorage(this.value)">
                        @php
                            $currentStorage = session('sandbox_storage', '1');
                            $storageOptions = [];
                            $storagePath = storage_path();

                            if (file_exists($storagePath)) {
                                $directories = glob($storagePath . '/storage-sandbox-*', GLOB_ONLYDIR);
                                foreach ($directories as $directory) {
                                    $basename = basename($directory);
                                    if (strpos($basename, 'storage-sandbox-') === 0) {
                                        $name = substr($basename, strlen('storage-sandbox-'));
                                        $storageOptions[] = $name;
                                    }
                                }
                                sort($storageOptions);
                            }
                        @endphp

                        @forelse($storageOptions as $storage)
                            <option value="{{ $storage }}" {{ $storage == $currentStorage ? 'selected' : '' }}>
                                storage-sandbox-{{ $storage }}
                            </option>
                        @empty
                            <option value="1">storage-sandbox-1 (기본)</option>
                        @endforelse
                    </select>
                </div>

                <a href="/sandbox/storage-manager"
                   class="text-sm text-blue-600 hover:text-blue-800 underline">
                    관리
                </a>
            </div>
        </div>

        <!-- 네비게이션 메뉴 -->
        <div class="pb-4">
            <!-- 시스템 관리 -->
            <div class="mb-3">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">시스템 관리</div>
                <div class="flex flex-wrap gap-2">
                    <a href="/sandbox/dashboard" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        대시보드
                    </a>
                    <a href="/sandbox/database-manager" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        데이터베이스 매니저
                    </a>
                    <a href="/sandbox/sql-executor" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        SQL 실행기
                    </a>
                    <a href="/sandbox/storage-manager" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        스토리지 관리자
                    </a>
                </div>
            </div>

            <!-- 파일 관리 -->
            <div class="mb-3">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">파일 관리</div>
                <div class="flex flex-wrap gap-2">
                    <a href="/sandbox/file-manager" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        파일 매니저
                    </a>
                    <a href="/sandbox/file-editor" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        파일 에디터
                    </a>
                </div>
            </div>

            <!-- 개발 도구 -->
            <div class="mb-2">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">개발 도구</div>
                <div class="flex flex-wrap gap-2">
                    <a href="/sandbox/api-creator" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        API 생성기
                    </a>
                    <a href="/sandbox/api-list" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        API 목록
                    </a>
                    <a href="/sandbox/blade-creator" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        Blade 생성기
                    </a>
                    <a href="/sandbox/blade-list" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        Blade 목록
                    </a>
                    <a href="/sandbox/git-version-control" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        Git 버전 관리
                    </a>
                    <a href="/sandbox/function-browser" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors font-medium">
                        함수 브라우저
                    </a>
                    <a href="/sandbox/form-creator" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition-colors font-medium">
                        Form Creator
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 스토리지 선택 함수
    function selectStorage(storageName) {
        // 폼 생성하여 POST 요청 전송
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/sandbox/storage-manager/select';

        // CSRF 토큰 추가
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);

        // 스토리지 이름 추가
        const storageInput = document.createElement('input');
        storageInput.type = 'hidden';
        storageInput.name = 'storage_name';
        storageInput.value = storageName;
        form.appendChild(storageInput);

        // 폼을 body에 추가하고 전송
        document.body.appendChild(form);
        form.submit();
    }
</script>
