{{-- 시스템 설정 메인 콘텐츠 --}}
<div class="system-settings-content" style="padding: 24px;" x-data="systemSettings">
    
    {{-- 설정 카테고리 탭 --}}
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'general'"
                        :class="activeTab === 'general' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    일반 설정
                </button>
                <button @click="activeTab = 'security'"
                        :class="activeTab === 'security' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    보안 설정
                </button>
                <button @click="activeTab = 'api'"
                        :class="activeTab === 'api' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    API 설정
                </button>
                <button @click="activeTab = 'notifications'"
                        :class="activeTab === 'notifications' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    알림 설정
                </button>
            </nav>
        </div>
    </div>

    {{-- 일반 설정 탭 --}}
    <div x-show="activeTab === 'general'">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- 플랫폼 기본 설정 --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">플랫폼 기본 설정</h3>
                <form @submit.prevent="saveGeneralSettings">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">플랫폼 이름</label>
                            <input type="text" 
                                   x-model="settings.general.platform_name"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">기본 언어</label>
                            <select x-model="settings.general.default_language"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="ko">한국어</option>
                                <option value="en">English</option>
                                <option value="ja">日本語</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">기본 시간대</label>
                            <select x-model="settings.general.default_timezone"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="Asia/Seoul">Asia/Seoul</option>
                                <option value="UTC">UTC</option>
                                <option value="America/New_York">America/New_York</option>
                            </select>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.general.maintenance_mode"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">유지보수 모드</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            저장
                        </button>
                    </div>
                </form>
            </div>

            {{-- 조직 기본 설정 --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">조직 기본 설정</h3>
                <form @submit.prevent="saveOrganizationDefaults">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">최대 멤버 수 (기본값)</label>
                            <input type="number" 
                                   x-model="settings.organization.default_max_members"
                                   min="1"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">최대 프로젝트 수 (기본값)</label>
                            <input type="number" 
                                   x-model="settings.organization.default_max_projects"
                                   min="1"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">기본 플랜</label>
                            <select x-model="settings.organization.default_plan"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="free">무료</option>
                                <option value="basic">베이직</option>
                                <option value="pro">프로</option>
                                <option value="enterprise">엔터프라이즈</option>
                            </select>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.organization.auto_approve_registration"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">조직 등록 자동 승인</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            저장
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 보안 설정 탭 --}}
    <div x-show="activeTab === 'security'">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- 인증 설정 --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">인증 설정</h3>
                <form @submit.prevent="saveSecuritySettings">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">세션 만료 시간 (분)</label>
                            <input type="number" 
                                   x-model="settings.security.session_timeout"
                                   min="5" max="1440"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">로그인 최대 시도 횟수</label>
                            <input type="number" 
                                   x-model="settings.security.max_login_attempts"
                                   min="1" max="10"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">계정 잠금 시간 (분)</label>
                            <input type="number" 
                                   x-model="settings.security.lockout_duration"
                                   min="1" max="1440"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.security.require_2fa"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">2단계 인증 필수</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.security.password_expires"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">비밀번호 만료 사용</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            저장
                        </button>
                    </div>
                </form>
            </div>

            {{-- 감사 로그 설정 --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">감사 로그 설정</h3>
                <form @submit.prevent="saveAuditSettings">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">로그 보관 기간 (일)</label>
                            <input type="number" 
                                   x-model="settings.audit.retention_days"
                                   min="1" max="3650"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">로그 레벨</label>
                            <select x-model="settings.audit.log_level"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="error">오류만</option>
                                <option value="warning">경고 이상</option>
                                <option value="info">정보 이상</option>
                                <option value="debug">모든 로그</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">로그 대상</label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.audit.log_user_actions"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">사용자 액션</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.audit.log_admin_actions"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">관리자 액션</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.audit.log_system_events"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">시스템 이벤트</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" 
                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                            저장
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- API 설정 탭 --}}
    <div x-show="activeTab === 'api'">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">API 설정</h3>
            <form @submit.prevent="saveApiSettings">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">API 요청 제한 (분당)</label>
                            <input type="number" 
                                   x-model="settings.api.rate_limit_per_minute"
                                   min="1"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">API 토큰 만료 기간 (일)</label>
                            <input type="number" 
                                   x-model="settings.api.token_expires_days"
                                   min="1" max="365"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.api.require_https"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">HTTPS 필수</span>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">허용된 도메인 (줄바꿈으로 구분)</label>
                            <textarea x-model="settings.api.allowed_origins"
                                      rows="4"
                                      placeholder="https://example.com&#10;https://app.example.com"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.api.enable_cors"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">CORS 활성화</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        저장
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 알림 설정 탭 --}}
    <div x-show="activeTab === 'notifications'">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">알림 설정</h3>
            <form @submit.prevent="saveNotificationSettings">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900">이메일 알림</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMTP 서버</label>
                            <input type="text" 
                                   x-model="settings.notifications.smtp_host"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMTP 포트</label>
                            <input type="number" 
                                   x-model="settings.notifications.smtp_port"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">발신자 이메일</label>
                            <input type="email" 
                                   x-model="settings.notifications.from_email"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900">알림 활성화</h4>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.notifications.user_registration"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">사용자 가입 알림</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.notifications.organization_created"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">조직 생성 알림</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.notifications.system_alerts"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">시스템 알림</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       x-model="settings.notifications.security_alerts"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">보안 알림</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        저장
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('systemSettings', () => ({
        activeTab: 'general',
        settings: {
            general: {
                platform_name: 'Plobin Platform',
                default_language: 'ko',
                default_timezone: 'Asia/Seoul',
                maintenance_mode: false
            },
            organization: {
                default_max_members: 10,
                default_max_projects: 5,
                default_plan: 'free',
                auto_approve_registration: false
            },
            security: {
                session_timeout: 720,
                max_login_attempts: 5,
                lockout_duration: 15,
                require_2fa: false,
                password_expires: false
            },
            audit: {
                retention_days: 90,
                log_level: 'info',
                log_user_actions: true,
                log_admin_actions: true,
                log_system_events: true
            },
            api: {
                rate_limit_per_minute: 100,
                token_expires_days: 30,
                require_https: true,
                allowed_origins: '',
                enable_cors: true
            },
            notifications: {
                smtp_host: '',
                smtp_port: 587,
                from_email: '',
                user_registration: true,
                organization_created: true,
                system_alerts: true,
                security_alerts: true
            }
        },

        init() {
            console.log('System settings initialized');
        },

        saveGeneralSettings() {
            console.log('Saving general settings:', this.settings.general);
            // 실제 구현시 API 호출
            this.showSuccessMessage('일반 설정이 저장되었습니다.');
        },

        saveOrganizationDefaults() {
            console.log('Saving organization defaults:', this.settings.organization);
            this.showSuccessMessage('조직 기본 설정이 저장되었습니다.');
        },

        saveSecuritySettings() {
            console.log('Saving security settings:', this.settings.security);
            this.showSuccessMessage('보안 설정이 저장되었습니다.');
        },

        saveAuditSettings() {
            console.log('Saving audit settings:', this.settings.audit);
            this.showSuccessMessage('감사 로그 설정이 저장되었습니다.');
        },

        saveApiSettings() {
            console.log('Saving API settings:', this.settings.api);
            this.showSuccessMessage('API 설정이 저장되었습니다.');
        },

        saveNotificationSettings() {
            console.log('Saving notification settings:', this.settings.notifications);
            this.showSuccessMessage('알림 설정이 저장되었습니다.');
        },

        showSuccessMessage(message) {
            // 실제 구현시 toast 메시지 표시
            alert(message);
        }
    }));
});
</script>