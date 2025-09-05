{{-- 조직 데이터 관리 클래스 --}}
<script>
/**
 * 조직 데이터 관리를 담당하는 클래스
 */
class OrganizationDataManager {
    constructor() {
        this.baseUrl = '/api/organizations';
    }

    /**
     * 인증 헤더를 가져옵니다
     */
    getAuthHeaders() {
        const token = localStorage.getItem('auth_token');
        return {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
    }

    /**
     * 조직 상세 정보를 로드합니다
     * @param {number} orgId - 조직 ID
     * @returns {Object|null} 조직 데이터
     */
    async loadOrganizationDetail(orgId) {
        try {
            console.log('조직 상세 정보 로드 시작:', orgId);

            const response = await fetch(`${this.baseUrl}/${orgId}`, {
                method: 'GET',
                headers: this.getAuthHeaders()
            });

            if (!response.ok) {
                throw new Error(`조직 정보 로드 실패: ${response.status}`);
            }

            const data = await response.json();
            console.log('조직 상세 정보 API 응답:', data);

            // 응답 데이터 구조에 따른 조직 정보 추출
            let orgData = null;
            if (data.success && data.data) {
                orgData = data.data;
            } else if (data.organization) {
                orgData = data.organization;
            } else if (data.name) {
                orgData = data;
            }

            return orgData;

        } catch (error) {
            console.error('조직 상세 정보 로드 실패:', error.message);
            ApiErrorHandler.handle(error, '조직 상세 정보 로드');
            return null;
        }
    }

    /**
     * 대시보드 통계를 로드합니다
     * @param {number} orgId - 조직 ID
     * @returns {Object|null} 통계 데이터
     */
    async loadDashboardStats(orgId) {
        try {
            console.log('대시보드 통계 로드 시작:', orgId);

            const response = await fetch(`${this.baseUrl}/${orgId}/stats`, {
                method: 'GET',
                headers: this.getAuthHeaders()
            });

            if (!response.ok) {
                if (response.status === 404) {
                    // 통계 API가 아직 구현되지 않은 경우 기본값 반환
                    console.warn('통계 API 미구현, 기본값 사용');
                    return this.getDefaultStats();
                }
                throw new Error(`통계 로드 실패: ${response.status}`);
            }

            const data = await response.json();
            console.log('대시보드 통계 API 응답:', data);

            // 응답 데이터 구조에 따른 통계 정보 추출
            let stats = null;
            if (data.success && data.data) {
                stats = data.data;
            } else if (data.stats) {
                stats = data.stats;
            } else {
                stats = data;
            }

            return stats || this.getDefaultStats();

        } catch (error) {
            console.error('대시보드 통계 로드 실패:', error.message);
            // API 에러 시 기본값 반환
            return this.getDefaultStats();
        }
    }

    /**
     * 최근 활동을 로드합니다
     * @param {number} orgId - 조직 ID
     * @returns {Array} 활동 목록
     */
    async loadRecentActivities(orgId) {
        try {
            console.log('최근 활동 로드 시작:', orgId);

            const response = await fetch(`${this.baseUrl}/${orgId}/activities?limit=5`, {
                method: 'GET',
                headers: this.getAuthHeaders()
            });

            if (!response.ok) {
                if (response.status === 404) {
                    // 활동 API가 아직 구현되지 않은 경우 기본값 반환
                    console.warn('활동 API 미구현, 기본값 사용');
                    return this.getDefaultActivities();
                }
                throw new Error(`활동 로드 실패: ${response.status}`);
            }

            const data = await response.json();
            console.log('최근 활동 API 응답:', data);

            // 응답 데이터 구조에 따른 활동 목록 추출
            let activities = [];
            if (data.success && data.data && Array.isArray(data.data)) {
                activities = data.data;
            } else if (data.activities && Array.isArray(data.activities)) {
                activities = data.activities;
            } else if (Array.isArray(data)) {
                activities = data;
            }

            return activities.length > 0 ? activities : this.getDefaultActivities();

        } catch (error) {
            console.error('최근 활동 로드 실패:', error.message);
            // API 에러 시 기본값 반환
            return this.getDefaultActivities();
        }
    }

    /**
     * 최근 프로젝트를 로드합니다
     * @param {number} orgId - 조직 ID
     * @returns {Array} 프로젝트 목록
     */
    async loadRecentProjects(orgId) {
        try {
            console.log('최근 프로젝트 로드 시작:', orgId);

            const response = await fetch(`${this.baseUrl}/${orgId}/projects?limit=5`, {
                method: 'GET',
                headers: this.getAuthHeaders()
            });

            if (!response.ok) {
                if (response.status === 404) {
                    // 프로젝트 API가 아직 구현되지 않은 경우 기본값 반환
                    console.warn('프로젝트 API 미구현, 기본값 사용');
                    return this.getDefaultProjects();
                }
                throw new Error(`프로젝트 로드 실패: ${response.status}`);
            }

            const data = await response.json();
            console.log('최근 프로젝트 API 응답:', data);

            // 응답 데이터 구조에 따른 프로젝트 목록 추출
            let projects = [];
            if (data.success && data.data && Array.isArray(data.data)) {
                projects = data.data;
            } else if (data.projects && Array.isArray(data.projects)) {
                projects = data.projects;
            } else if (Array.isArray(data)) {
                projects = data;
            }

            return projects.length > 0 ? projects : this.getDefaultProjects();

        } catch (error) {
            console.error('최근 프로젝트 로드 실패:', error.message);
            // API 에러 시 기본값 반환
            return this.getDefaultProjects();
        }
    }

    /**
     * 기본 통계 데이터를 반환합니다
     */
    getDefaultStats() {
        return {
            memberCount: 5,
            projectCount: 3,
            completedTasks: 28,
            activeUsers: 4
        };
    }

    /**
     * 기본 활동 데이터를 반환합니다
     */
    getDefaultActivities() {
        return [
            {
                title: '새 멤버가 조직에 참여했습니다',
                time: '2시간 전'
            },
            {
                title: '프로젝트 "222"이 생성되었습니다',
                time: '5시간 전'
            },
            {
                title: '작업 3개가 완료되었습니다',
                time: '1일 전'
            },
            {
                title: '조직 설정이 업데이트되었습니다',
                time: '2일 전'
            },
            {
                title: '새로운 공지사항이 등록되었습니다',
                time: '3일 전'
            }
        ];
    }

    /**
     * 기본 프로젝트 데이터를 반환합니다
     */
    getDefaultProjects() {
        return [
            {
                name: '웹사이트 리뉴얼',
                status: '진행중',
                members: 4
            },
            {
                name: '모바일 앱 개발',
                status: '진행중',
                members: 3
            },
            {
                name: 'API 서버 구축',
                status: '완료',
                members: 2
            },
            {
                name: 'UI/UX 디자인 시스템',
                status: '일시정지',
                members: 2
            }
        ];
    }

    /**
     * 조직 멤버 목록을 로드합니다
     * @param {number} orgId - 조직 ID
     * @returns {Array} 멤버 목록
     */
    async loadMembers(orgId) {
        try {
            const response = await fetch(`${this.baseUrl}/${orgId}/members`, {
                method: 'GET',
                headers: this.getAuthHeaders()
            });

            if (!response.ok) {
                throw new Error(`멤버 목록 로드 실패: ${response.status}`);
            }

            const data = await response.json();

            // 응답 데이터 구조에 따른 멤버 목록 추출
            let members = [];
            if (data.success && data.data && Array.isArray(data.data)) {
                members = data.data;
            } else if (data.members && Array.isArray(data.members)) {
                members = data.members;
            } else if (Array.isArray(data)) {
                members = data;
            }

            return members;

        } catch (error) {
            console.error('멤버 목록 로드 실패:', error.message);
            ApiErrorHandler.handle(error, '멤버 목록 로드');
            return [];
        }
    }

    /**
     * 멤버를 초대합니다
     * @param {number} orgId - 조직 ID
     * @param {string} email - 초대할 이메일
     * @param {string} role - 역할
     * @returns {boolean} 성공 여부
     */
    async inviteMember(orgId, email, role = 'member') {
        try {
            const response = await fetch(`${this.baseUrl}/${orgId}/invite`, {
                method: 'POST',
                headers: this.getAuthHeaders(),
                body: JSON.stringify({ email, role })
            });

            if (!response.ok) {
                throw new Error(`멤버 초대 실패: ${response.status}`);
            }

            const data = await response.json();
            return data.success || true;

        } catch (error) {
            console.error('멤버 초대 실패:', error.message);
            ApiErrorHandler.handle(error, '멤버 초대');
            return false;
        }
    }
}
</script>
