// Dashboard Sidebar JavaScript
class DashboardSidebar {
    constructor() {
        this.currentOrg = null;
        this.organizations = [];
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadOrganizations();
        this.setupNavigation();
    }

    bindEvents() {
        // 조직 선택 드롭다운 토글
        const orgSelector = document.getElementById('orgSelector');
        const orgDropdown = document.getElementById('orgDropdown');
        
        if (orgSelector && orgDropdown) {
            orgSelector.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleDropdown();
            });

            // 드롭다운 외부 클릭 시 닫기
            document.addEventListener('click', (e) => {
                if (!orgDropdown.contains(e.target) && !orgSelector.contains(e.target)) {
                    this.closeDropdown();
                }
            });
        }

        // 조직 검색
        const orgSearch = document.getElementById('orgSearch');
        if (orgSearch) {
            orgSearch.addEventListener('input', (e) => {
                this.filterOrganizations(e.target.value);
            });
        }

        // 새 조직 생성 버튼
        const createOrgBtn = document.getElementById('createOrgBtn');
        if (createOrgBtn) {
            createOrgBtn.addEventListener('click', () => {
                this.showCreateOrgModal();
            });
        }

        // 모바일 사이드바 토글
        this.setupMobileToggle();
    }

    async loadOrganizations() {
        try {
            const response = await fetch('/api/organizations', {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.organizations = data.data.organizations || [];
                this.renderOrganizations();
            } else {
                console.error('조직 목록 로드 실패:', response.status);
                this.organizations = [];
            }
        } catch (error) {
            console.error('조직 목록 로드 중 오류:', error);
            this.organizations = [];
        }
    }

    renderOrganizations() {
        const orgList = document.getElementById('orgList');
        if (!orgList) return;

        if (this.organizations.length === 0) {
            orgList.innerHTML = `
                <div class="org-empty">
                    <p style="padding: 20px; text-align: center; color: var(--text-secondary); font-size: 14px;">
                        생성된 조직이 없습니다.
                    </p>
                </div>
            `;
            return;
        }

        orgList.innerHTML = this.organizations.map(org => `
            <div class="org-item" data-org-id="${org.id}" data-org-url="${org.url}" 
                 style="display: flex; align-items: center; padding: 10px 12px; cursor: pointer; transition: background-color 0.2s ease;">
                <div>
                    <div class="org-item-name" style="font-size: 14px; color: #111111;">${this.escapeHtml(org.name)}</div>
                    <div class="org-item-url" style="font-size: 12px; color: #666666; margin-top: 2px;">@${this.escapeHtml(org.url)}</div>
                </div>
            </div>
        `).join('');

        // 조직 선택 이벤트 바인딩
        orgList.querySelectorAll('.org-item').forEach(item => {
            item.addEventListener('click', () => {
                const orgId = item.dataset.orgId;
                const orgUrl = item.dataset.orgUrl;
                this.selectOrganization(orgId, orgUrl);
            });
        });
    }

    filterOrganizations(query) {
        const orgItems = document.querySelectorAll('.org-item');
        const normalizedQuery = query.toLowerCase();

        orgItems.forEach(item => {
            const name = item.querySelector('.org-item-name').textContent.toLowerCase();
            const url = item.querySelector('.org-item-url').textContent.toLowerCase();
            
            if (name.includes(normalizedQuery) || url.includes(normalizedQuery)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    selectOrganization(orgId, orgUrl) {
        this.currentOrg = { id: orgId, url: orgUrl };
        
        // UI 업데이트
        const orgText = document.querySelector('.org-text');
        if (orgText) {
            orgText.textContent = `@${orgUrl}`;
        }

        // 로컬 스토리지에 저장
        localStorage.setItem('selectedOrg', JSON.stringify(this.currentOrg));
        
        // 드롭다운 닫기
        this.closeDropdown();
        
        // 페이지 새로고침 또는 필요한 데이터 로드
        this.onOrganizationChange(orgId);
    }

    onOrganizationChange(orgId) {
        // 조직 변경 시 실행될 로직
        console.log('조직이 변경되었습니다:', orgId);
        
        // 대시보드 데이터 새로고침 등
        if (typeof window.refreshDashboard === 'function') {
            window.refreshDashboard(orgId);
        }
    }

    toggleDropdown() {
        const orgSelector = document.getElementById('orgSelector');
        const orgDropdown = document.getElementById('orgDropdown');
        
        if (orgDropdown.classList.contains('show')) {
            this.closeDropdown();
        } else {
            this.openDropdown();
        }
    }

    openDropdown() {
        const orgSelector = document.getElementById('orgSelector');
        const orgDropdown = document.getElementById('orgDropdown');
        
        orgSelector.classList.add('open');
        // 인라인 스타일로 드롭다운 표시
        if (orgDropdown) {
            orgDropdown.style.opacity = '1';
            orgDropdown.style.visibility = 'visible';
            orgDropdown.style.transform = 'translateY(0)';
        }
        
        // 드롭다운 화살표 회전
        const arrow = orgSelector.querySelector('.dropdown-arrow');
        if (arrow) {
            arrow.style.transform = 'rotate(180deg)';
        }
        
        // 검색 인풋에 포커스
        const orgSearch = document.getElementById('orgSearch');
        if (orgSearch) {
            setTimeout(() => orgSearch.focus(), 100);
        }
    }

    closeDropdown() {
        const orgSelector = document.getElementById('orgSelector');
        const orgDropdown = document.getElementById('orgDropdown');
        
        orgSelector.classList.remove('open');
        
        // 인라인 스타일로 드롭다운 숨김
        if (orgDropdown) {
            orgDropdown.style.opacity = '0';
            orgDropdown.style.visibility = 'hidden';
            orgDropdown.style.transform = 'translateY(-10px)';
        }
        
        // 드롭다운 화살표 원상복구
        const arrow = orgSelector.querySelector('.dropdown-arrow');
        if (arrow) {
            arrow.style.transform = 'rotate(0deg)';
        }
        
        // 검색 초기화
        const orgSearch = document.getElementById('orgSearch');
        if (orgSearch) {
            orgSearch.value = '';
            this.filterOrganizations('');
        }
    }

    showCreateOrgModal() {
        // 모달 창 HTML 동적 생성
        const modalHtml = `
            <div class="modal-overlay" id="createOrgModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>새 조직 만들기</h3>
                        <button class="modal-close" type="button">&times;</button>
                    </div>
                    <form id="createOrgForm" class="modal-body">
                        <div class="form-group">
                            <label for="orgName">조직명 <span class="required">*</span></label>
                            <input type="text" id="orgName" name="name" maxlength="25" required
                                   placeholder="조직명을 입력하세요 (최대 25자)">
                            <div class="form-help">한글, 영문 모두 가능합니다.</div>
                        </div>
                        <div class="form-group">
                            <label for="orgUrl">조직 URL <span class="required">*</span></label>
                            <div class="input-prefix">
                                <span class="prefix">@</span>
                                <input type="text" id="orgUrl" name="url" 
                                       pattern="[a-zA-Z]{3,12}" minlength="3" maxlength="12" required
                                       placeholder="organizationurl">
                            </div>
                            <div class="form-help">3-12자 영문자만 가능합니다.</div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn-cancel">취소</button>
                            <button type="submit" class="btn-create">조직 만들기</button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        // 모달 추가
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // 모달 이벤트 바인딩
        this.bindModalEvents();
        
        // 모달 표시
        document.getElementById('createOrgModal').style.display = 'flex';
        document.getElementById('orgName').focus();
    }

    bindModalEvents() {
        const modal = document.getElementById('createOrgModal');
        const form = document.getElementById('createOrgForm');
        const closeBtn = modal.querySelector('.modal-close');
        const cancelBtn = modal.querySelector('.btn-cancel');

        // 닫기 버튼들
        [closeBtn, cancelBtn].forEach(btn => {
            btn.addEventListener('click', () => this.closeModal());
        });

        // 모달 외부 클릭
        modal.addEventListener('click', (e) => {
            if (e.target === modal) this.closeModal();
        });

        // ESC 키
        const escHandler = (e) => {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                this.closeModal();
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);

        // 폼 제출
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.createOrganization();
        });

        // URL 입력 실시간 검증
        const urlInput = document.getElementById('orgUrl');
        urlInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^a-zA-Z]/g, '');
        });
    }

    async createOrganization() {
        const form = document.getElementById('createOrgForm');
        const formData = new FormData(form);
        const createBtn = form.querySelector('.btn-create');
        
        // 로딩 상태
        createBtn.disabled = true;
        createBtn.textContent = '생성 중...';

        try {
            const response = await fetch('/api/organizations', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: formData.get('name'),
                    url: formData.get('url')
                })
            });

            const data = await response.json();

            if (response.ok) {
                // 성공 시
                this.closeModal();
                await this.loadOrganizations(); // 목록 새로고침
                
                // 새로 생성된 조직 자동 선택
                this.selectOrganization(data.data.id, data.data.url);
                
                // 성공 메시지 (선택적)
                this.showToast('조직이 성공적으로 생성되었습니다.', 'success');
            } else {
                // 에러 처리
                this.showFormErrors(data.errors || { general: [data.message] });
            }
        } catch (error) {
            console.error('조직 생성 중 오류:', error);
            this.showFormErrors({ general: ['네트워크 오류가 발생했습니다.'] });
        } finally {
            createBtn.disabled = false;
            createBtn.textContent = '조직 만들기';
        }
    }

    showFormErrors(errors) {
        // 기존 에러 메시지 제거
        document.querySelectorAll('.error-message').forEach(el => el.remove());

        for (const [field, messages] of Object.entries(errors)) {
            const input = field === 'general' ? null : document.getElementById(`org${field.charAt(0).toUpperCase() + field.slice(1)}`);
            
            messages.forEach(message => {
                const errorEl = document.createElement('div');
                errorEl.className = 'error-message';
                errorEl.textContent = message;
                errorEl.style.color = '#e53e3e';
                errorEl.style.fontSize = '12px';
                errorEl.style.marginTop = '4px';

                if (input) {
                    input.parentNode.appendChild(errorEl);
                    input.style.borderColor = '#e53e3e';
                } else {
                    document.querySelector('.form-actions').before(errorEl);
                }
            });
        }
    }

    closeModal() {
        const modal = document.getElementById('createOrgModal');
        if (modal) {
            modal.remove();
        }
    }

    setupMobileToggle() {
        // 모바일 메뉴 토글 버튼이 있다면 이벤트 바인딩
        const mobileToggle = document.getElementById('mobileMenuToggle');
        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                
                sidebar.classList.toggle('mobile-open');
                if (overlay) overlay.classList.toggle('show');
            });
        }
    }

    setupNavigation() {
        // 네비게이션 활성 상태 설정
        const currentPath = window.location.pathname;
        const navItems = document.querySelectorAll('.nav-item');
        
        navItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href === currentPath) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    getAuthToken() {
        // Laravel Sanctum 토큰 가져오기
        const token = document.querySelector('meta[name="auth-token"]')?.content;
        if (token) return token;
        
        // 로컬 스토리지에서 가져오기 (필요시)
        return localStorage.getItem('auth_token') || '';
    }

    showToast(message, type = 'info') {
        // 간단한 토스트 메시지
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        } text-white`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// 모달 스타일 추가
const modalStyles = `
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 24px 16px;
        border-bottom: 1px solid var(--sidebar-border);
    }

    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: var(--text-secondary);
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-body {
        padding: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
    }

    .required {
        color: #e53e3e;
    }

    .form-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--sidebar-border);
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        box-sizing: border-box;
    }

    .form-group input:focus {
        border-color: var(--primary-color);
    }

    .input-prefix {
        position: relative;
    }

    .prefix {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 14px;
    }

    .input-prefix input {
        padding-left: 28px;
    }

    .form-help {
        margin-top: 4px;
        font-size: 12px;
        color: var(--text-secondary);
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
    }

    .btn-cancel, .btn-create {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cancel {
        background: transparent;
        border: 1px solid var(--sidebar-border);
        color: var(--text-primary);
    }

    .btn-cancel:hover {
        background: var(--hover-bg);
    }

    .btn-create {
        background: var(--primary-color);
        border: 1px solid var(--primary-color);
        color: white;
    }

    .btn-create:hover {
        background: #0bb39a;
    }

    .btn-create:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
`;

// 스타일 추가
if (!document.getElementById('modal-styles')) {
    const style = document.createElement('style');
    style.id = 'modal-styles';
    style.textContent = modalStyles;
    document.head.appendChild(style);
}

// DOM이 로드되면 초기화
document.addEventListener('DOMContentLoaded', () => {
    window.dashboardSidebar = new DashboardSidebar();
});

// 저장된 조직 정보 복원
document.addEventListener('DOMContentLoaded', () => {
    const savedOrg = localStorage.getItem('selectedOrg');
    if (savedOrg) {
        try {
            const orgData = JSON.parse(savedOrg);
            const orgText = document.querySelector('.org-text');
            if (orgText && orgData.url) {
                orgText.textContent = `@${orgData.url}`;
            }
        } catch (e) {
            localStorage.removeItem('selectedOrg');
        }
    }
});