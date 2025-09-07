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
</script>