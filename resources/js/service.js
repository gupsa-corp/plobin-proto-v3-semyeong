// Service Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // 모바일 사이드바 토글
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.service-sidebar');
    const overlay = document.createElement('div');
    
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-30 hidden';
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

    // 네비게이션 활성 상태
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.service-nav-item');
    
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPath) {
            item.classList.add('active');
        }
    });

    // 알림 시스템
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        } text-white`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // 전역 알림 함수 등록
    window.showNotification = showNotification;

    // AJAX 요청 헬퍼
    window.serviceAPI = {
        get: async function(url) {
            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                return await response.json();
            } catch (error) {
                showNotification('요청 처리 중 오류가 발생했습니다.', 'error');
                throw error;
            }
        },
        
        post: async function(url, data) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                });
                return await response.json();
            } catch (error) {
                showNotification('요청 처리 중 오류가 발생했습니다.', 'error');
                throw error;
            }
        }
    };

    console.log('Service page loaded');
});