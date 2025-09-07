{{-- 권한 관리 페이지 JavaScript --}}
<script>
// 탭 전환 기능
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // 모든 탭 비활성화
            tabLinks.forEach(l => {
                l.classList.remove('border-indigo-500', 'text-indigo-600', 'active');
                l.classList.add('border-transparent', 'text-gray-500');
            });
            
            // 모든 탭 콘텐츠 숨기기
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // 선택된 탭 활성화
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-indigo-500', 'text-indigo-600', 'active');
            
            // 해당 탭 콘텐츠 표시
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId + '-tab').classList.remove('hidden');
        });
    });
});

// 모달 관련 함수
function openCreateRoleModal() {
    document.getElementById('create-role-modal').classList.remove('hidden');
}

function closeCreateRoleModal() {
    document.getElementById('create-role-modal').classList.add('hidden');
}

// 모달 외부 클릭시 닫기
document.addEventListener('click', function(event) {
    const modal = document.getElementById('create-role-modal');
    if (event.target === modal) {
        closeCreateRoleModal();
    }
});

// ESC 키로 모달 닫기
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCreateRoleModal();
        closeRoleChangeModal();
        closeStatusChangeModal();
        closeTenantPermissionModal();
    }
});

// 사용자 관리 관련 함수
let currentUserId = null;
let currentUserName = null;
let currentUserTenantPermissions = [];

// 역할 변경 모달 관련
function openRoleChangeModal(userId) {
    currentUserId = userId;
    // 사용자 이름 찾기
    const userRow = document.querySelector(`button[onclick="openRoleChangeModal(${userId})"]`).closest('tr');
    currentUserName = userRow.querySelector('.text-sm.font-medium.text-gray-900').textContent;
    
    document.getElementById('selectedUserName').textContent = currentUserName;
    document.getElementById('roleChangeModal').classList.remove('hidden');
}

function closeRoleChangeModal() {
    document.getElementById('roleChangeModal').classList.add('hidden');
    currentUserId = null;
    currentUserName = null;
    document.getElementById('newRole').value = '';
}

function saveRoleChange() {
    const newRole = document.getElementById('newRole').value;
    if (!newRole) {
        alert('역할을 선택해주세요.');
        return;
    }
    
    if (confirm(`정말 ${currentUserName}의 역할을 변경하시겠습니까?`)) {
        // AJAX 요청 (실제 구현시 추가)
        fetch('/platform/admin/permissions/users/change-role', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: currentUserId,
                role: newRole
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('역할이 변경되었습니다.');
                location.reload(); // 페이지 새로고침
            } else {
                alert('역할 변경에 실패했습니다.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('오류가 발생했습니다.');
        });
        
        closeRoleChangeModal();
    }
}

// 사용자 상태 변경 모달 관련
function toggleUserStatus(userId) {
    currentUserId = userId;
    // 사용자 이름 찾기
    const userRow = document.querySelector(`button[onclick="toggleUserStatus(${userId})"]`).closest('tr');
    currentUserName = userRow.querySelector('.text-sm.font-medium.text-gray-900').textContent;
    
    document.getElementById('statusChangeMessage').textContent = 
        `정말 ${currentUserName}을(를) 비활성화 하시겠습니까?`;
    document.getElementById('statusChangeModal').classList.remove('hidden');
}

function closeStatusChangeModal() {
    document.getElementById('statusChangeModal').classList.add('hidden');
    currentUserId = null;
    currentUserName = null;
}

function confirmStatusChange() {
    // AJAX 요청 (실제 구현시 추가)
    fetch('/platform/admin/permissions/users/toggle-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            user_id: currentUserId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('사용자 상태가 변경되었습니다.');
            location.reload(); // 페이지 새로고침
        } else {
            alert('상태 변경에 실패했습니다.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('오류가 발생했습니다.');
    });
    
    closeStatusChangeModal();
}

// 테넌트별 권한 관리 모달 관련
function openTenantPermissionModal(userId) {
    currentUserId = userId;
    // 사용자 이름 찾기
    const userRow = document.querySelector(`button[onclick="openTenantPermissionModal(${userId})"]`).closest('tr');
    currentUserName = userRow.querySelector('.text-sm.font-medium.text-gray-900').textContent;
    
    document.getElementById('selectedUserNameTenant').textContent = currentUserName;
    
    // 사용자의 현재 테넌트 권한 정보 로드
    loadUserTenantPermissions(userId);
    
    document.getElementById('tenantPermissionModal').classList.remove('hidden');
}

function closeTenantPermissionModal() {
    document.getElementById('tenantPermissionModal').classList.add('hidden');
    currentUserId = null;
    currentUserName = null;
    currentUserTenantPermissions = [];
    document.getElementById('newOrganization').value = '';
    document.getElementById('newPermissionLevel').value = '';
    document.getElementById('currentTenantPermissions').innerHTML = '';
}

function loadUserTenantPermissions(userId) {
    // 현재 테이블에서 사용자의 조직 권한 정보를 추출
    const userRow = document.querySelector(`button[onclick="openTenantPermissionModal(${userId})"]`).closest('tr');
    const orgPermissionCell = userRow.querySelector('td:nth-child(3)');
    
    currentUserTenantPermissions = [];
    const permissionElements = orgPermissionCell.querySelectorAll('.flex.items-center.space-x-2');
    
    permissionElements.forEach(elem => {
        const orgName = elem.querySelector('.text-xs.text-gray-600').textContent.trim();
        const permission = elem.querySelector('.inline-flex').textContent.trim();
        currentUserTenantPermissions.push({ orgName, permission, elem });
    });
    
    renderCurrentTenantPermissions();
}

function renderCurrentTenantPermissions() {
    const container = document.getElementById('currentTenantPermissions');
    container.innerHTML = '';
    
    if (currentUserTenantPermissions.length === 0) {
        container.innerHTML = '<p class="text-sm text-gray-500">현재 조직 권한이 없습니다.</p>';
        return;
    }
    
    currentUserTenantPermissions.forEach((perm, index) => {
        const permissionDiv = document.createElement('div');
        permissionDiv.className = 'flex items-center justify-between p-2 border rounded';
        permissionDiv.innerHTML = `
            <div class="flex items-center space-x-2">
                <span class="font-medium">${perm.orgName}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    ${perm.permission}
                </span>
            </div>
            <div class="flex space-x-2">
                <button onclick="editTenantPermission(${index})" class="text-indigo-600 hover:text-indigo-900 text-xs">수정</button>
                <button onclick="removeTenantPermission(${index})" class="text-red-600 hover:text-red-900 text-xs">제거</button>
            </div>
        `;
        container.appendChild(permissionDiv);
    });
}

function addTenantPermission() {
    const orgId = document.getElementById('newOrganization').value;
    const permissionLevel = document.getElementById('newPermissionLevel').value;
    
    if (!orgId || !permissionLevel) {
        alert('조직과 권한 레벨을 모두 선택해주세요.');
        return;
    }
    
    const orgName = document.getElementById('newOrganization').selectedOptions[0].text;
    const permissionText = document.getElementById('newPermissionLevel').selectedOptions[0].text;
    
    // 중복 체크
    const exists = currentUserTenantPermissions.find(p => p.orgName === orgName);
    if (exists) {
        alert('이미 해당 조직에 권한이 설정되어 있습니다.');
        return;
    }
    
    currentUserTenantPermissions.push({
        orgId,
        orgName,
        permissionLevel,
        permission: permissionText,
        isNew: true
    });
    
    renderCurrentTenantPermissions();
    
    // 폼 리셋
    document.getElementById('newOrganization').value = '';
    document.getElementById('newPermissionLevel').value = '';
}

function editTenantPermission(index) {
    // 간단한 구현: 제거 후 다시 추가하도록 안내
    if (confirm('권한을 수정하려면 제거 후 다시 추가해주세요. 제거하시겠습니까?')) {
        removeTenantPermission(index);
    }
}

function removeTenantPermission(index) {
    if (confirm('정말 이 권한을 제거하시겠습니까?')) {
        currentUserTenantPermissions.splice(index, 1);
        renderCurrentTenantPermissions();
    }
}

function saveTenantPermissions() {
    const permissions = currentUserTenantPermissions.map(p => ({
        organization_id: p.orgId,
        permission_level: p.permissionLevel,
        is_new: p.isNew || false
    }));
    
    // AJAX 요청
    fetch('/platform/admin/permissions/users/update-tenant-permissions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            user_id: currentUserId,
            permissions: permissions
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('권한이 성공적으로 업데이트되었습니다.');
            location.reload();
        } else {
            alert('권한 업데이트에 실패했습니다.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('오류가 발생했습니다.');
    });
    
    closeTenantPermissionModal();
}

// 모달 외부 클릭시 닫기 - 추가 모달들
document.addEventListener('click', function(event) {
    const roleModal = document.getElementById('roleChangeModal');
    const statusModal = document.getElementById('statusChangeModal');
    const tenantModal = document.getElementById('tenantPermissionModal');
    
    if (event.target === roleModal) {
        closeRoleChangeModal();
    }
    if (event.target === statusModal) {
        closeStatusChangeModal();
    }
    if (event.target === tenantModal) {
        closeTenantPermissionModal();
    }
});

// 사용자 검색 및 필터링 기능
function searchUsers(searchTerm) {
    const rows = document.querySelectorAll('#usersTableBody tr');
    const term = searchTerm.toLowerCase();
    
    rows.forEach(row => {
        if (row.querySelector('td[colspan]')) return; // 빈 행은 제외
        
        const name = row.querySelector('.text-sm.font-medium.text-gray-900')?.textContent.toLowerCase() || '';
        const email = row.querySelector('.text-sm.text-gray-500')?.textContent.toLowerCase() || '';
        const organization = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
        
        const matches = name.includes(term) || email.includes(term) || organization.includes(term);
        row.style.display = matches ? '' : 'none';
    });
    
    updateEmptyState();
}

function filterByRole(role) {
    const rows = document.querySelectorAll('#usersTableBody tr');
    
    rows.forEach(row => {
        if (row.querySelector('td[colspan]')) return; // 빈 행은 제외
        
        const roleElements = row.querySelectorAll('td:nth-child(2) .inline-flex');
        let hasRole = false;
        
        if (role === '') {
            hasRole = true; // 모든 역할 표시
        } else if (role === 'no_role') {
            hasRole = roleElements.length === 0 || 
                     (roleElements.length === 1 && roleElements[0].textContent.includes('역할 없음'));
        } else {
            const roleText = role === 'platform_admin' ? '플랫폼 관리자' :
                           role === 'organization_admin' ? '조직 관리자' :
                           role === 'organization_member' ? '조직 멤버' : role;
            
            Array.from(roleElements).forEach(elem => {
                if (elem.textContent.includes(roleText)) {
                    hasRole = true;
                }
            });
        }
        
        row.style.display = hasRole ? '' : 'none';
    });
    
    updateEmptyState();
}

function filterByOrganization(orgId) {
    const rows = document.querySelectorAll('#usersTableBody tr');
    
    rows.forEach(row => {
        if (row.querySelector('td[colspan]')) return; // 빈 행은 제외
        
        if (orgId === '') {
            row.style.display = ''; // 모든 조직 표시
            return;
        }
        
        const orgPermissionCell = row.querySelector('td:nth-child(3)');
        let hasOrganization = false;
        
        if (orgId === 'no_org') {
            // 조직 소속 없음 체크
            hasOrganization = orgPermissionCell.textContent.includes('조직 소속 없음');
        } else {
            // 특정 조직 체크
            const orgElements = orgPermissionCell.querySelectorAll('.text-xs.text-gray-600');
            Array.from(orgElements).forEach(elem => {
                const orgName = elem.textContent.trim();
                // 조직 ID로 매칭하는 것이 더 정확하지만, 현재는 이름으로 매칭
                if (elem.title && elem.title.includes(orgName)) {
                    hasOrganization = true;
                }
            });
        }
        
        row.style.display = hasOrganization ? '' : 'none';
    });
    
    updateEmptyState();
}

function filterByPermission(permissionLevel) {
    const rows = document.querySelectorAll('#usersTableBody tr');
    
    rows.forEach(row => {
        if (row.querySelector('td[colspan]')) return; // 빈 행은 제외
        
        if (permissionLevel === '') {
            row.style.display = ''; // 모든 권한 표시
            return;
        }
        
        const orgPermissionCell = row.querySelector('td:nth-child(3)');
        let hasPermission = false;
        
        // 권한 레벨에 따른 텍스트 매핑
        const permissionText = {
            '0': '초대됨',
            '100': '사용자',
            '150': '사용자+',
            '200': '서비스 매니저',
            '250': '서비스 매니저+',
            '300': '관리자',
            '350': '관리자+',
            '400': '소유자',
            '450': '창립자',
            '500': '플랫폼 관리자',
            '550': '최고 관리자'
        };
        
        const targetPermission = permissionText[permissionLevel];
        if (targetPermission) {
            hasPermission = orgPermissionCell.textContent.includes(targetPermission);
        }
        
        row.style.display = hasPermission ? '' : 'none';
    });
    
    updateEmptyState();
}

function clearFilters() {
    // 검색어 초기화
    document.getElementById('userSearchInput').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('organizationFilter').value = '';
    document.getElementById('permissionFilter').value = '';
    
    // 모든 행 표시
    const rows = document.querySelectorAll('#usersTableBody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
    
    updateEmptyState();
}

function updateEmptyState() {
    const rows = document.querySelectorAll('#usersTableBody tr');
    const visibleRows = Array.from(rows).filter(row => 
        row.style.display !== 'none' && !row.querySelector('td[colspan]')
    );
    
    const emptyRow = document.querySelector('#usersTableBody tr td[colspan]');
    if (emptyRow) {
        emptyRow.closest('tr').style.display = visibleRows.length === 0 ? '' : 'none';
        if (visibleRows.length === 0) {
            emptyRow.textContent = '검색 결과가 없습니다.';
        }
    }
}

// 페이지 로드시 초기화
document.addEventListener('DOMContentLoaded', function() {
    // 사용자 테이블에 ID 추가 (검색을 위해)
    const tbody = document.querySelector('tbody');
    if (tbody) {
        tbody.id = 'usersTableBody';
    }
});

// === 권한 관리 전용 JavaScript ===

// 전역 변수
let permissionsData = {};
let rolesData = [];
let isBulkMode = false;
let selectedPermissions = new Set();

// 초기 데이터 로드
function loadInitialData() {
    Promise.all([
        fetch('/api/platform/admin/permissions/matrix'),
        fetch('/api/platform/admin/permissions/stats')
    ])
    .then(responses => Promise.all(responses.map(r => r.json())))
    .then(([matrixData, statsData]) => {
        if (matrixData.success) {
            permissionsData = matrixData.data.permissions;
            rolesData = matrixData.data.roles;
            renderPermissionMatrix(matrixData.data.matrix);
            populateCategoryFilter();
        }
        
        if (statsData.success) {
            updateStats(statsData.data);
        }
        
        hideLoading();
    })
    .catch(error => {
        console.error('Failed to load initial data:', error);
        showError('데이터 로드에 실패했습니다.');
        hideLoading();
    });
}

// 권한 매트릭스 렌더링
function renderPermissionMatrix(matrix) {
    const table = document.getElementById('permissions-matrix-table');
    const thead = table?.querySelector('thead tr');
    const tbody = document.getElementById('permissions-matrix-body');
    
    if (!table || !thead || !tbody) return;
    
    // 헤더 생성 (역할들)
    thead.innerHTML = `
        <th class="sticky left-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">
            권한
        </th>
    `;
    
    rolesData.forEach(role => {
        const th = document.createElement('th');
        th.className = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';
        th.innerHTML = `
            <div class="flex flex-col items-center">
                <span class="mb-1">${role}</span>
                <button onclick="selectAllForRole('${role}')" class="text-xs text-blue-600 hover:text-blue-800">
                    전체선택
                </button>
            </div>
        `;
        thead.appendChild(th);
    });
    
    // 본문 생성
    tbody.innerHTML = '';
    
    Object.keys(permissionsData).forEach(category => {
        // 카테고리 헤더
        const categoryRow = document.createElement('tr');
        categoryRow.className = 'category-header';
        categoryRow.innerHTML = `
            <td colspan="${rolesData.length + 1}" class="px-6 py-2 font-semibold text-gray-900 bg-gray-100">
                ${category}
            </td>
        `;
        tbody.appendChild(categoryRow);
        
        // 권한 행들
        permissionsData[category].forEach(permission => {
            const row = document.createElement('tr');
            row.className = 'permission-row';
            row.dataset.permission = permission.name;
            row.dataset.category = category;
            
            let rowHtml = `
                <td class="sticky left-0 z-10 bg-white px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-200">
                    <div class="flex items-center">
                        <div>
                            <div>${permission.name}</div>
                            ${permission.description ? `<div class="text-xs text-gray-500">${permission.description}</div>` : ''}
                        </div>
                    </div>
                </td>
            `;
            
            rolesData.forEach(role => {
                const isChecked = matrix[role] && matrix[role][category] && matrix[role][category][permission.name];
                rowHtml += `
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <input type="checkbox" 
                               class="permission-checkbox" 
                               data-role="${role}" 
                               data-permission="${permission.name}"
                               ${isChecked ? 'checked' : ''}
                               onchange="toggleRolePermission('${role}', '${permission.name}', this.checked)">
                    </td>
                `;
            });
            
            row.innerHTML = rowHtml;
            tbody.appendChild(row);
        });
    });
    
    document.getElementById('matrix-loading')?.classList.add('hidden');
    table?.classList.remove('hidden');
}

// 카테고리 필터 채우기
function populateCategoryFilter() {
    const select = document.getElementById('category-filter');
    if (!select) return;
    
    // 기존 옵션 제거 (첫 번째 제외)
    while (select.children.length > 1) {
        select.removeChild(select.lastChild);
    }
    
    Object.keys(permissionsData).forEach(category => {
        const option = document.createElement('option');
        option.value = category;
        option.textContent = category;
        select.appendChild(option);
    });
}

// 통계 업데이트
function updateStats(stats) {
    const totalPermEl = document.getElementById('total-permissions');
    const activeRolesEl = document.getElementById('active-roles');
    const totalCatEl = document.getElementById('total-categories');
    
    if (totalPermEl) totalPermEl.textContent = stats.total_permissions || 0;
    if (activeRolesEl) activeRolesEl.textContent = stats.total_roles || 0;
    if (totalCatEl) totalCatEl.textContent = Object.keys(stats.permissions_by_category || {}).length;
}

// 권한 검색 처리
function handlePermissionSearch() {
    const searchInput = document.getElementById('permission-search');
    if (!searchInput) return;
    
    const searchTerm = searchInput.value.toLowerCase();
    const rows = document.querySelectorAll('.permission-row');
    
    rows.forEach(row => {
        const permissionName = row.dataset.permission.toLowerCase();
        const permissionDesc = row.querySelector('.text-xs')?.textContent?.toLowerCase() || '';
        const category = row.dataset.category.toLowerCase();
        
        const matches = permissionName.includes(searchTerm) || 
                       permissionDesc.includes(searchTerm) || 
                       category.includes(searchTerm);
        
        row.style.display = matches ? '' : 'none';
    });
    
    updateCategoryHeaders();
}

// 카테고리 필터 처리
function handleCategoryFilter() {
    const categoryFilter = document.getElementById('category-filter');
    if (!categoryFilter) return;
    
    const selectedCategory = categoryFilter.value;
    const rows = document.querySelectorAll('.permission-row');
    const categoryHeaders = document.querySelectorAll('.category-header');
    
    if (!selectedCategory) {
        rows.forEach(row => row.style.display = '');
        categoryHeaders.forEach(header => header.style.display = '');
    } else {
        rows.forEach(row => {
            row.style.display = row.dataset.category === selectedCategory ? '' : 'none';
        });
        
        categoryHeaders.forEach(header => {
            const categoryName = header.textContent.trim();
            header.style.display = categoryName === selectedCategory ? '' : 'none';
        });
    }
}

// 역할 권한 토글
function toggleRolePermission(role, permission, isChecked) {
    const data = {
        role_name: role,
        permissions: []
    };
    
    // 해당 역할의 모든 권한 상태를 수집
    const roleCheckboxes = document.querySelectorAll(`[data-role="${role}"]`);
    roleCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            data.permissions.push(checkbox.dataset.permission);
        }
    });
    
    // API 호출
    fetch('/api/platform/admin/permissions/roles/permissions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (!result.success) {
            // 실패시 체크박스 상태 되돌리기
            const checkbox = document.querySelector(`[data-role="${role}"][data-permission="${permission}"]`);
            checkbox.checked = !isChecked;
            showError('권한 업데이트에 실패했습니다.');
        } else {
            showSuccess('권한이 업데이트되었습니다.');
        }
    })
    .catch(error => {
        console.error('Permission update failed:', error);
        const checkbox = document.querySelector(`[data-role="${role}"][data-permission="${permission}"]`);
        checkbox.checked = !isChecked;
        showError('권한 업데이트 중 오류가 발생했습니다.');
    });
}

// 역할별 전체 선택
function selectAllForRole(role) {
    const checkboxes = document.querySelectorAll(`[data-role="${role}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
    
    // 권한 업데이트
    const permissions = allChecked ? [] : Array.from(checkboxes).map(cb => cb.dataset.permission);
    
    fetch('/api/platform/admin/permissions/roles/permissions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            role_name: role,
            permissions: permissions
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showSuccess(`${role} 역할의 권한이 ${allChecked ? '제거' : '할당'}되었습니다.`);
        } else {
            showError('권한 업데이트에 실패했습니다.');
            checkboxes.forEach(checkbox => {
                checkbox.checked = allChecked;
            });
        }
    })
    .catch(error => {
        console.error('Bulk permission update failed:', error);
        showError('권한 업데이트 중 오류가 발생했습니다.');
        checkboxes.forEach(checkbox => {
            checkbox.checked = allChecked;
        });
    });
}

// 일괄 관리 모드 토글
function toggleBulkMode() {
    isBulkMode = !isBulkMode;
    const bulkPanel = document.getElementById('bulk-actions-panel');
    
    if (isBulkMode) {
        bulkPanel?.classList.remove('hidden');
        showBulkCheckboxes();
    } else {
        bulkPanel?.classList.add('hidden');
        selectedPermissions.clear();
        hideBulkCheckboxes();
    }
}

// 일괄 체크박스 표시
function showBulkCheckboxes() {
    document.querySelectorAll('.permission-row').forEach(row => {
        const permissionName = row.dataset.permission;
        const cell = row.querySelector('td div');
        
        // 이미 체크박스가 있는지 확인
        if (!cell.querySelector('.bulk-checkbox')) {
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'bulk-checkbox permission-checkbox mr-3';
            checkbox.dataset.permission = permissionName;
            checkbox.onchange = () => togglePermissionSelection(permissionName);
            
            cell.insertBefore(checkbox, cell.firstChild);
        }
    });
}

// 일괄 체크박스 숨김
function hideBulkCheckboxes() {
    document.querySelectorAll('.bulk-checkbox').forEach(cb => cb.remove());
}

// 권한 선택 토글
function togglePermissionSelection(permission) {
    if (selectedPermissions.has(permission)) {
        selectedPermissions.delete(permission);
    } else {
        selectedPermissions.add(permission);
    }
    
    const countEl = document.getElementById('selected-count');
    if (countEl) countEl.textContent = selectedPermissions.size;
}

// 일괄 모드 취소
function cancelBulkMode() {
    toggleBulkMode();
}

// 권한 생성 모달 열기
function openCreatePermissionModal() {
    const name = prompt('권한 이름을 입력하세요:');
    if (!name) return;
    
    const category = prompt('카테고리를 입력하세요:');
    if (!category) return;
    
    const description = prompt('설명을 입력하세요 (선택사항):') || '';
    
    createPermission(name, category, description);
}

// 권한 생성
function createPermission(name, category, description) {
    fetch('/api/platform/admin/permissions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            name: name,
            category: category,
            description: description,
            guard_name: 'web'
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showSuccess('새 권한이 생성되었습니다.');
            loadInitialData();
        } else {
            showError('권한 생성에 실패했습니다.');
        }
    })
    .catch(error => {
        console.error('Permission creation failed:', error);
        showError('권한 생성 중 오류가 발생했습니다.');
    });
}

// 권한 내보내기
function exportPermissions() {
    fetch('/api/platform/admin/permissions/export')
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            const dataStr = JSON.stringify(result.data, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            const url = URL.createObjectURL(dataBlob);
            
            const link = document.createElement('a');
            link.href = url;
            link.download = `permissions-export-${new Date().toISOString().split('T')[0]}.json`;
            link.click();
            
            URL.revokeObjectURL(url);
            showSuccess('권한 데이터가 내보내졌습니다.');
        } else {
            showError('내보내기에 실패했습니다.');
        }
    })
    .catch(error => {
        console.error('Export failed:', error);
        showError('내보내기 중 오류가 발생했습니다.');
    });
}

// 카테고리 헤더 업데이트
function updateCategoryHeaders() {
    const categoryHeaders = document.querySelectorAll('.category-header');
    
    categoryHeaders.forEach(header => {
        const categoryName = header.textContent.trim();
        const categoryRows = document.querySelectorAll(`[data-category="${categoryName}"]`);
        const visibleRows = Array.from(categoryRows).filter(row => row.style.display !== 'none');
        
        header.style.display = visibleRows.length > 0 ? '' : 'none';
    });
}

// 유틸리티 함수들
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function hideLoading() {
    document.getElementById('matrix-loading')?.classList.add('hidden');
}

function showError(message) {
    alert('오류: ' + message);
}

function showSuccess(message) {
    alert('성공: ' + message);
}

// 이벤트 리스너 설정
document.addEventListener('DOMContentLoaded', function() {
    // 권한 관리 탭에서만 실행
    if (window.location.pathname.includes('/permissions/permissions')) {
        setTimeout(() => {
            const searchInput = document.getElementById('permission-search');
            if (searchInput) {
                searchInput.addEventListener('input', debounce(handlePermissionSearch, 300));
            }
            
            const categoryFilter = document.getElementById('category-filter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', handleCategoryFilter);
            }
            
            loadInitialData();
        }, 100);
    }
});

</script>