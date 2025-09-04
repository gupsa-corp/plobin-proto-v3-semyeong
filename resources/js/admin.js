// Admin Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // 관리자 사이드바 토글
    const sidebarToggle = document.getElementById('admin-sidebar-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    const overlay = document.createElement('div');
    
    overlay.className = 'fixed inset-0 bg-black bg-opacity-75 z-30 hidden';
    document.body.appendChild(overlay);

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.add('hidden');
        });
    }

    // 관리자 네비게이션 활성 상태
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.admin-nav-item');
    
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPath) {
            item.classList.add('active');
        }
    });

    // 확인 대화상자
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    confirmButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });

    // 테이블 행 선택
    const selectAllCheckbox = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('input[name="selected_items[]"]');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAll();
            updateBulkActions();
        });
    });

    function updateSelectAll() {
        const checkedCount = document.querySelectorAll('input[name="selected_items[]"]:checked').length;
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedCount === rowCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < rowCheckboxes.length;
        }
    }

    function updateBulkActions() {
        const checkedCount = document.querySelectorAll('input[name="selected_items[]"]:checked').length;
        const bulkActions = document.querySelector('.bulk-actions');
        
        if (bulkActions) {
            if (checkedCount > 0) {
                bulkActions.classList.remove('hidden');
                const countSpan = bulkActions.querySelector('.selected-count');
                if (countSpan) {
                    countSpan.textContent = checkedCount;
                }
            } else {
                bulkActions.classList.add('hidden');
            }
        }
    }

    // 실시간 상태 업데이트
    function updateSystemStatus() {
        fetch('/admin/api/status')
            .then(response => response.json())
            .then(data => {
                const statusElements = document.querySelectorAll('[data-status]');
                statusElements.forEach(element => {
                    const statusType = element.getAttribute('data-status');
                    if (data[statusType]) {
                        element.textContent = data[statusType];
                        element.className = `admin-status ${data[statusType].toLowerCase()}`;
                    }
                });
            })
            .catch(error => {
                console.error('Status update failed:', error);
            });
    }

    // 5초마다 상태 업데이트
    setInterval(updateSystemStatus, 5000);

    // 관리자 알림 시스템
    function showAdminNotification(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
            type === 'success' ? 'bg-green-600' : 
            type === 'error' ? 'bg-red-600' : 
            type === 'warning' ? 'bg-yellow-600' :
            'bg-blue-600'
        } text-white max-w-sm`;
        
        notification.innerHTML = `
            <div class="flex justify-between items-start">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">×</button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        if (duration > 0) {
            setTimeout(() => {
                notification.remove();
            }, duration);
        }
    }

    // 전역 함수 등록
    window.showAdminNotification = showAdminNotification;

    // 관리자 API 헬퍼
    window.adminAPI = {
        delete: async function(url, data = {}) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showAdminNotification(result.message || '삭제되었습니다.', 'success');
                } else {
                    showAdminNotification(result.message || '삭제에 실패했습니다.', 'error');
                }
                
                return result;
            } catch (error) {
                showAdminNotification('요청 처리 중 오류가 발생했습니다.', 'error');
                throw error;
            }
        }
    };

    console.log('Admin page loaded');
});