{{-- Admin Page Styles - Dark Theme --}}
<style>
/* 관리자 전용 변수 */
:root {
    --admin-bg: #111827;
    --admin-sidebar: #1f2937;
    --admin-header: #1f2937;
    --admin-card: #374151;
    --admin-border: #4b5563;
    --admin-text: #f9fafb;
    --admin-text-secondary: #d1d5db;
    --admin-accent: #3b82f6;
    --admin-danger: #ef4444;
    --admin-success: #10b981;
    --admin-warning: #f59e0b;
}

/* 기본 다크 테마 */
.admin-body {
    background: var(--admin-bg);
    color: var(--admin-text);
}

/* 사이드바 스타일 */
.admin-sidebar {
    background: var(--admin-sidebar);
    border-right: 1px solid var(--admin-border);
}

.admin-nav-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: var(--admin-text-secondary);
    transition: all 0.2s ease;
    border-radius: 0.375rem;
    margin: 0.25rem 0.5rem;
}

.admin-nav-item:hover {
    background: var(--admin-card);
    color: var(--admin-text);
}

.admin-nav-item.active {
    background: var(--admin-accent);
    color: white;
}

/* 헤더 스타일 */
.admin-header {
    background: var(--admin-header);
    border-bottom: 1px solid var(--admin-border);
}

/* 카드 스타일 */
.admin-card {
    background: var(--admin-card);
    border: 1px solid var(--admin-border);
    border-radius: 0.5rem;
}

/* 상태 표시 */
.admin-status {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.admin-status.success {
    background: var(--admin-success);
    color: white;
}

.admin-status.danger {
    background: var(--admin-danger);
    color: white;
}

.admin-status.warning {
    background: var(--admin-warning);
    color: white;
}

/* 버튼 스타일 */
.btn-admin {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-admin-primary {
    background: var(--admin-accent);
    color: white;
}

.btn-admin-primary:hover {
    background: #2563eb;
}

.btn-admin-danger {
    background: var(--admin-danger);
    color: white;
}

.btn-admin-danger:hover {
    background: #dc2626;
}

/* 테이블 스타일 */
.admin-table {
    background: var(--admin-card);
    border: 1px solid var(--admin-border);
}

.admin-table th {
    background: var(--admin-sidebar);
    color: var(--admin-text);
    padding: 0.75rem;
    border-bottom: 1px solid var(--admin-border);
}

.admin-table td {
    padding: 0.75rem;
    border-bottom: 1px solid var(--admin-border);
    color: var(--admin-text-secondary);
}

/* 반응형 */
@media (max-width: 768px) {
    .admin-sidebar {
        position: fixed;
        left: -16rem;
        transition: left 0.3s ease;
        z-index: 40;
    }

    .admin-sidebar.open {
        left: 0;
    }
}
</style>

