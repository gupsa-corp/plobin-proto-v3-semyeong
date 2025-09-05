{{-- 조직 관리자 모달 --}}
<div id="organizationManagerModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 50; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; border-radius: 8px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;">
        {{-- 모달 헤더 --}}
        <div class="modal-header" style="padding: 20px; border-bottom: 1px solid #E1E1E4; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 18px; font-weight: 600; color: #1F2937; margin: 0;">조직 관리</h3>
            <button class="modal-close" onclick="closeOrganizationModal()" style="background: none; border: none; font-size: 24px; color: #6B7280; cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                ×
            </button>
        </div>

        {{-- 모달 본문 --}}
        <div class="modal-body" style="padding: 20px;">
            {{-- 현재 조직 정보 --}}
            <div style="margin-bottom: 24px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1F2937; margin-bottom: 12px;">현재 조직</h4>
                <div style="padding: 16px; background-color: #F9FAFB; border-radius: 6px; border: 1px solid #E5E7EB;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                            <span style="color: white; font-weight: bold; font-size: 16px;">기</span>
                        </div>
                        <div>
                            <p style="font-size: 16px; font-weight: 600; color: #1F2937; margin: 0;">기본 조직</p>
                            <p style="font-size: 14px; color: #6B7280; margin: 0;">무료 플랜 • 멤버 1명</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 조직 목록 --}}
            <div style="margin-bottom: 24px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1F2937; margin-bottom: 12px;">내 조직</h4>
                <div id="organizationList">
                    {{-- 조직이 없을 때 표시 --}}
                    <div class="empty-state" style="text-align: center; padding: 32px 16px;">
                        <div style="width: 48px; height: 48px; background-color: #F3F4F6; border-radius: 50%; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 24px; height: 24px; color: #9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <p style="font-size: 16px; font-weight: 500; color: #1F2937; margin: 0 0 8px 0;">조직이 없습니다</p>
                        <p style="font-size: 14px; color: #6B7280; margin: 0;">새 조직을 만들어 시작해보세요</p>
                    </div>
                </div>
            </div>

            {{-- 새 조직 생성 버튼 --}}
            <div style="text-align: center;">
                <button onclick="createNewOrganization()" style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); color: white; border: none; padding: 12px 24px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: opacity 0.2s;">
                    조직 만들기
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for modal functionality --}}
<script>
function openOrganizationModal() {
    const modal = document.getElementById('organizationManagerModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeOrganizationModal() {
    const modal = document.getElementById('organizationManagerModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function createNewOrganization() {
    alert('조직 생성 기능은 아직 구현되지 않았습니다.');
}

// 모달 외부 클릭 시 닫기
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('organizationManagerModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeOrganizationModal();
            }
        });
    }
});

// ESC 키로 모달 닫기
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeOrganizationModal();
    }
});
</script>