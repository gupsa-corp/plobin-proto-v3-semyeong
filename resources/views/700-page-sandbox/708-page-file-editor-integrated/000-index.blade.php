<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '통합 파일 에디터'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen">
        <div class="container-fluid px-4 py-8">
            <div class="max-w-full mx-auto">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-800">통합 파일 에디터</h2>
                        <p class="text-sm text-gray-600 mt-1">파일 매니저와 Monaco 에디터를 함께 사용하는 통합 개발 환경</p>
                    </div>
                    
                    <div class="flex" style="height: calc(100vh - 200px);">
                        <!-- 왼쪽: 파일 매니저 -->
                        <div class="w-1/3 border-r border-gray-200 bg-gray-50">
                            <div class="h-full overflow-auto">
                                <div class="p-4 border-b bg-white">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">파일 매니저</h3>
                                    <p class="text-sm text-gray-600">파일을 업로드하고 편집할 파일을 선택하세요</p>
                                </div>
                                
                                <div class="p-4">
                                    <x-livewire-filemanager />
                                </div>
                            </div>
                        </div>
                        
                        <!-- 오른쪽: Monaco 에디터 -->
                        <div class="w-2/3 bg-white">
                            <livewire:sandbox.file-manager-editor />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* 파일매니저 커스터마이징 */
        .livewire-filemanager {
            height: 100%;
        }
        
        /* 모나코 에디터 다크 테마 조정 */
        .monaco-editor {
            border-radius: 0;
        }
        
        /* 스크롤바 스타일링 */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // 파일매니저의 파일 클릭을 감지하여 에디터로 전달
            document.addEventListener('click', function(e) {
                // '.file' 클래스를 가진 요소 클릭 감지 (파일매니저의 파일 아이템)
                const fileElement = e.target.closest('.file');
                if (fileElement) {
                    const mediaId = fileElement.dataset.id;
                    if (mediaId) {
                        // 약간의 지연 후 에디터 컴포넌트에 이벤트 전달
                        setTimeout(() => {
                            Livewire.dispatch('fileSelected', { mediaId: parseInt(mediaId) });
                        }, 100);
                    }
                }
            });

            // 파일 저장 성공 알림
            Livewire.on('fileSaved', (data) => {
                alert(data[0].message || '파일이 저장되었습니다.');
            });
        });
    </script>
    
    @livewireScripts
</body>
</html>