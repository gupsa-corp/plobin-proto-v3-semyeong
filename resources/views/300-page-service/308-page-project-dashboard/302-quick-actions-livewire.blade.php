{{-- 빠른 액션 섹션 --}}
<div style="padding: 20px; border-top: 1px solid #E5E7EB;">
    <span style="font-size: 12px; font-weight: 500; color: #6B7280; display: block; margin-bottom: 12px;">프로젝트 관리</span>

    <button wire:click="createNewPage"
            style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 8px; color: #6B7280; border: none; background: none; border-radius: 6px; cursor: pointer; margin-bottom: 4px;"
            onmouseover="this.style.background='white'"
            onmouseout="this.style.background='none'">
        <span style="font-size: 14px;">프로젝트 설정</span>
    </button>

    <button wire:click="manageTemplates"
            style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 8px; color: #6B7280; border: none; background: none; border-radius: 6px; cursor: pointer;"
            onmouseover="this.style.background='white'"
            onmouseout="this.style.background='none'">
        <span style="font-size: 14px;">페이지 수정</span>
    </button>
</div>
