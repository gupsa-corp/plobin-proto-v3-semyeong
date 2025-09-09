{{-- 페이지 목록 Livewire 컴포넌트 --}}
<div x-on:page-order-changed.window="
        const detail = $event.detail;
        $wire.call('updatePageOrder', detail.pageId, detail.newIndex, detail.beforePageId, detail.afterPageId);
     ">
    {{-- 동적 페이지 목록 --}}
    <div id="sortable-pages" style="display: flex; flex-direction: column; gap: 4px;">
        @if(count($pages) > 0)
            @foreach($pages as $page)
                @include('300-page-service.308-page-project-dashboard.302-page-tree-item', [
                    'page' => $page, 
                    'currentPage' => $currentPage, 
                    'orgId' => $orgId,
                    'projectId' => $projectId, 
                    'level' => 0
                ])
            @endforeach
        @else
            {{-- 빈 상태 --}}
            @if(!$isLoading)
                <div style="padding: 20px; text-align: center; color: #6B7280;">
                    <svg style="width: 32px; height: 32px; margin: 0 auto 8px; color: #D1D5DB;" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L19.7071 9.70711C19.8946 9.89464 20 10.149 20 10.4142V19C20 20.1046 19.1046 21 18 21H17Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <p style="font-size: 14px; margin: 0;">아직 페이지가 없습니다</p>
                    <p style="font-size: 12px; margin: 4px 0 0 0;">+ 버튼을 눌러 첫 페이지를 만들어보세요</p>
                </div>
            @endif
        @endif

        {{-- 로딩 상태 --}}
        @if($isLoading)
            <div style="padding: 20px; text-align: center; color: #6B7280;">
                <div style="width: 20px; height: 20px; border: 2px solid #E5E7EB; border-top: 2px solid #3B82F6; border-radius: 50%; margin: 0 auto; animation: spin 1s linear infinite;"></div>
                <p style="font-size: 14px; margin: 8px 0 0 0;">로딩 중...</p>
            </div>
        @endif
    </div>

    {{-- SortableJS 드래그 앤 드롭 --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortableContainer = document.getElementById('sortable-pages');
        
        if (sortableContainer && !sortableContainer.sortableInstance) {
            sortableContainer.sortableInstance = new Sortable(sortableContainer, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen', 
                dragClass: 'sortable-drag',
                handle: '.drag-handle',
                onStart: function(evt) {
                    // 드래그 시작 시 모든 드롭다운 닫기
                    document.querySelectorAll('[x-data]').forEach(el => {
                        if (el.__x && el.__x.$data && el.__x.$data.dropdownOpen) {
                            el.__x.$data.dropdownOpen = false;
                        }
                    });
                },
                onEnd: function(evt) {
                    const item = evt.item;
                    const pageId = item.dataset.pageId;
                    
                    // 새로운 위치 계산
                    const newIndex = evt.newIndex;
                    const allItems = Array.from(sortableContainer.children);
                    
                    // 이전/이후 형제 찾기
                    let beforePageId = null;
                    let afterPageId = null;
                    
                    if (newIndex > 0) {
                        const beforeItem = allItems[newIndex - 1];
                        beforePageId = beforeItem.dataset.pageId;
                    }
                    
                    if (newIndex < allItems.length - 1) {
                        const afterItem = allItems[newIndex + 1];
                        afterPageId = afterItem.dataset.pageId;
                    }
                    
                    // Livewire 이벤트로 전달
                    window.dispatchEvent(new CustomEvent('page-order-changed', {
                        detail: {
                            pageId: pageId,
                            newIndex: newIndex,
                            beforePageId: beforePageId,
                            afterPageId: afterPageId
                        }
                    }));
                }
            });
        }
    });

    // Livewire 훅: 컴포넌트가 업데이트될 때마다 SortableJS 재초기화
    document.addEventListener('livewire:updated', function() {
        const sortableContainer = document.getElementById('sortable-pages');
        if (sortableContainer && !sortableContainer.sortableInstance) {
            // 위의 SortableJS 초기화 코드 재실행
            sortableContainer.sortableInstance = new Sortable(sortableContainer, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen', 
                dragClass: 'sortable-drag',
                handle: '.drag-handle',
                onStart: function(evt) {
                    document.querySelectorAll('[x-data]').forEach(el => {
                        if (el.__x && el.__x.$data && el.__x.$data.dropdownOpen) {
                            el.__x.$data.dropdownOpen = false;
                        }
                    });
                },
                onEnd: function(evt) {
                    const item = evt.item;
                    const pageId = item.dataset.pageId;
                    const newIndex = evt.newIndex;
                    const allItems = Array.from(sortableContainer.children);
                    
                    let beforePageId = null;
                    let afterPageId = null;
                    
                    if (newIndex > 0) {
                        beforePageId = allItems[newIndex - 1].dataset.pageId;
                    }
                    if (newIndex < allItems.length - 1) {
                        afterPageId = allItems[newIndex + 1].dataset.pageId;
                    }
                    
                    // Livewire 이벤트로 전달
                    window.dispatchEvent(new CustomEvent('page-order-changed', {
                        detail: {
                            pageId: pageId,
                            newIndex: newIndex,
                            beforePageId: beforePageId,
                            afterPageId: afterPageId
                        }
                    }));
                }
            });
        }
    });
    </script>

    <style>
    .sortable-ghost {
        opacity: 0.4;
    }

    .sortable-chosen {
        cursor: grabbing !important;
    }

    .sortable-drag {
        opacity: 1;
    }

    .drag-handle {
        cursor: grab;
    }

    .drag-handle:active {
        cursor: grabbing;
    }

    /* Livewire 컴포넌트 보호 */
    .page-dropdown-container {
        position: relative;
        z-index: 9998;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>

    {{-- 드롭다운 관리 스크립트 --}}
    <script>
    // 글로벌 드롭다운 관리
    if (typeof currentOpenDropdownId === 'undefined') {
        let currentOpenDropdownId = null;

        function toggleDropdown(pageId) {
            closeAllDropdowns();
            
            if (currentOpenDropdownId !== pageId) {
                const dropdown = document.getElementById('dropdown-' + pageId);
                if (dropdown) {
                    dropdown.style.display = 'block';
                    currentOpenDropdownId = pageId;
                }
            } else {
                currentOpenDropdownId = null;
            }
        }

        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
                dropdown.style.display = 'none';
            });
            currentOpenDropdownId = null;
        }

        // 문서 클릭 시 드롭다운 닫기
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.page-dropdown-container')) {
                closeAllDropdowns();
            }
        });

        // 전역 함수로 등록
        window.toggleDropdown = toggleDropdown;
        window.closeAllDropdowns = closeAllDropdowns;
    }
    </script>
</div>