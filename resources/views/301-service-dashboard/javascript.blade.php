<script>
// 인증 상태 확인
async function checkAuth() {
    const token = localStorage.getItem('auth_token');

    // 디버깅용 로그
    console.log('저장된 토큰:', token);

    if (!token) {
        // 임시 토큰 설정 (개발용)
        console.log('토큰이 없습니다. 임시 토큰을 설정합니다.');
        localStorage.setItem('auth_token', 'temp_token_for_development');
        showDashboard({ name: '개발 사용자' });
        return;
    }

    // 임시 토큰인 경우 API 호출 스킵
    if (token === 'temp_token_for_development') {
        showDashboard({ name: '개발 사용자' });
        return;
    }

    try {
        const userData = await fetchUserInfo(token);
        showDashboard(userData);
    } catch (error) {
        console.log('인증 확인 오류:', error);
        // API가 없을 수 있으므로 토큰이 있으면 임시로 인증된 것으로 처리
        if (token) {
            showDashboard({ name: '사용자' });
        } else {
            showAuthRequired();
        }
    }
}

function showAuthRequired() {
    document.getElementById('authLoading').classList.add('hidden');
    document.getElementById('authRequired').classList.remove('hidden');
    document.getElementById('dashboardContent').classList.add('hidden');
}

function showDashboard(userData) {
    document.getElementById('authLoading').classList.add('hidden');
    document.getElementById('authRequired').classList.add('hidden');
    document.getElementById('dashboardContent').classList.remove('hidden');

    // 사용자 정보 표시
    if (userData && userData.name) {
        // userName 요소가 존재하는지 확인 후 업데이트
        const userNameElement = document.getElementById('userName');
        if (userNameElement) {
            userNameElement.textContent = userData.name;
        }

        // 헤더의 사용자 정보도 업데이트
        const userButton = document.querySelector('.service-header button span');
        if (userButton) {
            userButton.textContent = userData.name;
        }

        // 사용자 아바타 업데이트
        const userAvatar = document.querySelector('.service-header .bg-primary-500');
        if (userAvatar && userData.name) {
            userAvatar.textContent = userData.name.charAt(0).toUpperCase();
        }
    }
    
    console.log('대시보드 표시됨:', userData);
    
    // 조직 목록 로드
    loadOrganizations();
}

// 로그아웃 기능
async function logout() {
    try {
        await performLogout();
    } finally {
        window.location.href = '/login';
    }
}

// 페이지 로드시 인증 확인
document.addEventListener('DOMContentLoaded', checkAuth);

// 조직 목록 로드 함수
async function loadOrganizations() {
    try {
        // 임시 조직 데이터 (실제로는 API에서 받아올 예정)
        const organizations = [
            {
                id: 1,
                name: '겁쟁이사자들 천안지사',
                code: 'acmelotteeeeidsdfesd',
                avatar: 'G',
                avatar_color: '#0DC8AF'
            }
        ];

        const organizationList = document.getElementById('organizationList');
        organizationList.innerHTML = '';

        organizations.forEach(org => {
            const orgElement = createOrganizationElement(org);
            organizationList.appendChild(orgElement);
        });

    } catch (error) {
        console.error('조직 목록 로드 실패:', error);
    }
}

// 조직 카드 생성 함수
function createOrganizationElement(org) {
    const div = document.createElement('div');
    div.className = 'bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-200 p-5 cursor-pointer';
    div.onclick = () => selectOrganization(org.id);

    div.innerHTML = `
        <div class="flex flex-col h-full">
            <div class="flex justify-between items-center mb-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-2xl" style="background-color: ${org.avatar_color}">
                    ${org.avatar}
                </div>
                <div class="relative">
                    <button class="w-10 h-10 flex items-center justify-center hover:bg-gray-100 rounded-full" onclick="event.stopPropagation(); showOrganizationMenu(${org.id})">
                        <div class="flex flex-col gap-1">
                            <div class="w-1 h-1 bg-gray-800 rounded-full"></div>
                            <div class="w-1 h-1 bg-gray-800 rounded-full"></div>
                            <div class="w-1 h-1 bg-gray-800 rounded-full"></div>
                        </div>
                    </button>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-1">${org.name}</h3>
                <div class="flex items-center text-gray-500 text-base">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="mr-2">
                        <path d="M8 8V6C8 3.79086 9.79086 2 12 2C14.2091 2 16 3.79086 16 6V8M8 8H16M8 8H6C4.89543 8 4 8.89543 4 10V18C4 19.1046 4.89543 20 6 20H18C19.1046 20 20 19.1046 20 18V10C20 8.89543 19.1046 8 18 8H16" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    ${org.code}
                </div>
            </div>
        </div>
    `;

    return div;
}

// 조직 선택 함수
function selectOrganization(orgId) {
    console.log('조직 선택:', orgId);
    // TODO: 조직 선택 처리 로직
}

// 조직 메뉴 표시 함수
function showOrganizationMenu(orgId) {
    console.log('조직 메뉴 표시:', orgId);
    // TODO: 조직 메뉴 처리 로직
}

// 모달 관련 함수들
function showCreateOrganizationModal() {
    const modal = document.getElementById('createOrganizationModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // 입력 필드 초기화
        document.getElementById('orgName').value = '';
        document.getElementById('subdomain').value = '';
    }
}

function hideCreateOrganizationModal() {
    const modal = document.getElementById('createOrganizationModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// 성공 모달 표시 함수
function showCreateOrganizationSuccessModal(orgName, subdomain) {
    const modal = document.getElementById('createOrganizationSuccessModal');
    if (modal) {
        // 제목 업데이트
        const successTitle = document.getElementById('successTitle');
        if (successTitle) {
            successTitle.textContent = `${orgName} 조직이 생성되었습니다`;
        }

        // URL 업데이트
        const organizationUrl = document.getElementById('organizationUrl');
        if (organizationUrl) {
            organizationUrl.textContent = `www.plobin.com/orgs/${subdomain}`;
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

// 성공 모달 닫기 함수
function hideCreateOrganizationSuccessModal() {
    const modal = document.getElementById('createOrganizationSuccessModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// 중복 확인 함수
async function checkDuplicate() {
    const subdomain = document.getElementById('subdomain').value.trim();
    
    if (!subdomain) {
        alert('하위 도메인을 입력해주세요.');
        return;
    }

    // 영문 소문자 3~12자 유효성 검사
    const subdomainPattern = /^[a-z]{3,12}$/;
    if (!subdomainPattern.test(subdomain)) {
        alert('하위 도메인은 영문 소문자 3~12자로 입력해주세요.');
        return;
    }

    try {
        // TODO: 실제 API 연동
        console.log('중복 확인:', subdomain);
        
        // 임시로 사용 가능하다고 가정
        alert('사용 가능한 도메인입니다.');
        
    } catch (error) {
        console.error('중복 확인 실패:', error);
        alert('중복 확인 중 오류가 발생했습니다.');
    }
}

// 조직 생성 함수
async function createOrganization() {
    const orgName = document.getElementById('orgName').value.trim();
    const subdomain = document.getElementById('subdomain').value.trim();
    
    // 유효성 검사
    if (!orgName) {
        alert('조직 이름을 입력해주세요.');
        return;
    }

    if (orgName.length < 1 || orgName.length > 25) {
        alert('조직 이름은 1~25자로 입력해주세요.');
        return;
    }

    if (!subdomain) {
        alert('하위 도메인을 입력해주세요.');
        return;
    }

    const subdomainPattern = /^[a-z]{3,12}$/;
    if (!subdomainPattern.test(subdomain)) {
        alert('하위 도메인은 영문 소문자 3~12자로 입력해주세요.');
        return;
    }

    try {
        // TODO: 실제 API 연동
        console.log('조직 생성:', { orgName, subdomain });
        
        // 생성 모달 닫기
        hideCreateOrganizationModal();
        
        // 성공 모달 표시
        showCreateOrganizationSuccessModal(orgName, subdomain);
        
        // 조직 목록 다시 로드
        loadOrganizations();
        
    } catch (error) {
        console.error('조직 생성 실패:', error);
        alert('조직 생성 중 오류가 발생했습니다.');
    }
}

// 새 조직 생성 버튼 이벤트
document.addEventListener('DOMContentLoaded', function() {
    const createOrgBtn = document.getElementById('createOrganizationBtn');
    if (createOrgBtn) {
        createOrgBtn.addEventListener('click', function() {
            showCreateOrganizationModal();
        });
    }

    // 모달 닫기 버튼 이벤트
    const closeModalBtn = document.getElementById('closeModalBtn');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', hideCreateOrganizationModal);
    }

    // 모달 배경 클릭시 닫기
    const modal = document.getElementById('createOrganizationModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideCreateOrganizationModal();
            }
        });
    }

    // 중복 확인 버튼 이벤트
    const checkDuplicateBtn = document.getElementById('checkDuplicateBtn');
    if (checkDuplicateBtn) {
        checkDuplicateBtn.addEventListener('click', checkDuplicate);
    }

    // 생성하기 버튼 이벤트
    const createOrgSubmitBtn = document.getElementById('createOrgSubmitBtn');
    if (createOrgSubmitBtn) {
        createOrgSubmitBtn.addEventListener('click', createOrganization);
    }

    // 성공 모달 닫기 버튼 이벤트
    const closeSuccessModalBtn = document.getElementById('closeSuccessModalBtn');
    if (closeSuccessModalBtn) {
        closeSuccessModalBtn.addEventListener('click', hideCreateOrganizationSuccessModal);
    }

    // 성공 모달 확인 버튼 이벤트
    const successConfirmBtn = document.getElementById('successConfirmBtn');
    if (successConfirmBtn) {
        successConfirmBtn.addEventListener('click', hideCreateOrganizationSuccessModal);
    }

    // 성공 모달 배경 클릭시 닫기
    const successModal = document.getElementById('createOrganizationSuccessModal');
    if (successModal) {
        successModal.addEventListener('click', function(e) {
            if (e.target === successModal) {
                hideCreateOrganizationSuccessModal();
            }
        });
    }

    // ESC 키로 모달 닫기
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const createModal = document.getElementById('createOrganizationModal');
            const successModal = document.getElementById('createOrganizationSuccessModal');
            
            if (createModal && !createModal.classList.contains('hidden')) {
                hideCreateOrganizationModal();
            } else if (successModal && !successModal.classList.contains('hidden')) {
                hideCreateOrganizationSuccessModal();
            }
        }
    });
});

// 로그아웃 버튼에 이벤트 리스너 추가 (header에서 클릭시)
document.addEventListener('click', function(e) {
    if (e.target.getAttribute('href') === '/logout') {
        e.preventDefault();
        logout();
    }
});
</script>
