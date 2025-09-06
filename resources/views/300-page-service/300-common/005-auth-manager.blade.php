{{-- 중앙집중식 인증 관리자 --}}
<script>
/**
 * 중앙집중식 인증 관리 클래스
 * 모든 인증 관련 기능을 통합 관리
 */
class AuthManager {
    constructor() {
        this.token = null;
        this.user = null;
        this.isInitialized = false;
        this.listeners = {};
        
        // 초기화
        this.init();
    }

    /**
     * 초기화 함수
     */
    init() {
        if (this.isInitialized) return;
        
        this.token = this.getStoredToken();
        this.isInitialized = true;
        
        // 토큰이 있으면 사용자 정보 로드 시도
        if (this.token) {
            this.validateToken();
        }
        
        console.log('AuthManager 초기화 완료');
    }

    // ====================================================================
    // 토큰 관리
    // ====================================================================

    /**
     * 저장된 토큰 가져오기
     */
    getStoredToken() {
        return localStorage.getItem('auth_token');
    }

    /**
     * 토큰 저장
     */
    setToken(token) {
        if (!token) return;
        
        this.token = token;
        localStorage.setItem('auth_token', token);
        this.emit('tokenSet', token);
    }

    /**
     * 토큰 제거
     */
    removeToken() {
        this.token = null;
        this.user = null;
        localStorage.removeItem('auth_token');
        this.emit('tokenRemoved');
    }

    /**
     * 토큰 존재 여부 확인
     */
    hasToken() {
        return !!this.token;
    }

    /**
     * 토큰 유효성 검사
     */
    async validateToken() {
        if (!this.token) return false;
        
        try {
            const response = await this.makeRequest('/api/auth/verify', { 
                method: 'GET' 
            });
            
            if (response.success && response.user) {
                this.user = response.user;
                this.emit('userLoaded', response.user);
                return true;
            } else {
                this.removeToken();
                return false;
            }
        } catch (error) {
            console.warn('토큰 검증 실패:', error);
            this.removeToken();
            return false;
        }
    }

    // ====================================================================
    // 사용자 관리
    // ====================================================================

    /**
     * 현재 사용자 정보 가져오기
     */
    getUser() {
        return this.user;
    }

    /**
     * 사용자 정보 설정
     */
    setUser(user) {
        this.user = user;
        this.emit('userUpdated', user);
    }

    /**
     * 로그인 여부 확인
     */
    isAuthenticated() {
        return !!(this.token && this.user);
    }

    // ====================================================================
    // HTTP 요청 관리
    // ====================================================================

    /**
     * API 헤더 생성
     */
    getApiHeaders(customHeaders = {}) {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...customHeaders
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        } else {
            // CSRF 토큰 사용 (웹 세션)
            const csrfToken = this.getCsrfToken();
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
        }

        return headers;
    }

    /**
     * CSRF 토큰 가져오기
     */
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || document.querySelector('input[name="_token"]')?.value
            || '';
    }

    /**
     * 통합 HTTP 요청 함수
     */
    async makeRequest(url, options = {}) {
        const defaultOptions = {
            headers: this.getApiHeaders(),
            credentials: 'include',
            ...options
        };

        // 헤더 병합
        if (options.headers) {
            defaultOptions.headers = {
                ...defaultOptions.headers,
                ...options.headers
            };
        }

        try {
            const response = await fetch(url, defaultOptions);
            
            // 401 에러 시 자동 로그아웃
            if (response.status === 401) {
                this.handleUnauthorized();
                throw new Error('인증이 필요합니다.');
            }
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `서버 오류: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            // 네트워크 오류 처리
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                throw new Error('서버에 연결할 수 없습니다. 네트워크 연결을 확인해주세요.');
            }
            throw error;
        }
    }

    // ====================================================================
    // 인증 작업
    // ====================================================================

    /**
     * 로그인 처리
     */
    async login(email, password, remember = false) {
        try {
            const response = await this.makeRequest('/api/auth/login', {
                method: 'POST',
                body: JSON.stringify({
                    email: email,
                    password: password,
                    remember: remember
                })
            });

            if (response.success || response.token || (response.data && response.data.token)) {
                // 토큰 저장
                const token = response.token || (response.data && response.data.token);
                if (token) {
                    this.setToken(token);
                }

                // 사용자 정보 저장
                const user = response.user || (response.data && response.data.user);
                if (user) {
                    this.setUser(user);
                }

                this.emit('loginSuccess', { user, token, response });

                return {
                    success: true,
                    data: response,
                    redirectUrl: response.redirect_url || (response.data && response.data.redirect_url) || '/dashboard'
                };
            } else {
                const errorMsg = this.extractErrorMessage(response);
                return {
                    success: false,
                    message: errorMsg
                };
            }
        } catch (error) {
            console.error('로그인 요청 중 오류:', error);
            return {
                success: false,
                message: error.message || '로그인에 실패했습니다.'
            };
        }
    }

    /**
     * 로그아웃 처리
     */
    async logout(redirectUrl = '/login') {
        try {
            await this.makeRequest('/api/auth/logout', {
                method: 'POST'
            });
        } catch (error) {
            console.warn('로그아웃 요청 실패:', error);
        } finally {
            // 응답과 상관없이 클라이언트 토큰 제거
            this.removeToken();
            this.emit('logoutSuccess');
            window.location.href = redirectUrl;
        }
    }

    /**
     * 회원가입 처리 (참고용 - 실제로는 사용하지 않음)
     */
    async signup(userData) {
        try {
            const response = await this.makeRequest('/api/auth/signup', {
                method: 'POST',
                body: JSON.stringify(userData)
            });

            if (response.success) {
                // 토큰이 있다면 저장
                if (response.data && response.data.token) {
                    this.setToken(response.data.token);
                    this.setUser(response.data.user);
                }

                this.emit('signupSuccess', response);

                return {
                    success: true,
                    data: response.data,
                    message: response.message || '회원가입이 완료되었습니다.',
                    redirectUrl: response.redirect_url || '/dashboard'
                };
            } else {
                const errorMsg = this.extractErrorMessage(response);
                return {
                    success: false,
                    message: errorMsg,
                    errors: response.errors || {}
                };
            }
        } catch (error) {
            console.error('회원가입 요청 중 오류:', error);
            return {
                success: false,
                message: error.message || '회원가입에 실패했습니다.'
            };
        }
    }

    /**
     * 비밀번호 찾기
     */
    async forgotPassword(email) {
        try {
            const response = await this.makeRequest('/api/auth/forgot-password', {
                method: 'POST',
                body: JSON.stringify({ email })
            });

            if (response.success) {
                return {
                    success: true,
                    message: response.message || '비밀번호 재설정 링크가 이메일로 전송되었습니다.'
                };
            } else {
                const errorMsg = this.extractErrorMessage(response);
                return {
                    success: false,
                    message: errorMsg
                };
            }
        } catch (error) {
            console.error('비밀번호 찾기 요청 중 오류:', error);
            return {
                success: false,
                message: error.message || '요청 처리 중 오류가 발생했습니다.'
            };
        }
    }

    /**
     * 비밀번호 재설정
     */
    async resetPassword(resetData) {
        try {
            const response = await this.makeRequest('/api/auth/reset-password', {
                method: 'POST',
                body: JSON.stringify(resetData)
            });

            if (response.success) {
                return {
                    success: true,
                    message: response.message || '비밀번호가 성공적으로 변경되었습니다.',
                    redirectUrl: '/login'
                };
            } else {
                const errorMsg = this.extractErrorMessage(response);
                return {
                    success: false,
                    message: errorMsg,
                    errors: response.errors || {}
                };
            }
        } catch (error) {
            console.error('비밀번호 재설정 요청 중 오류:', error);
            return {
                success: false,
                message: error.message || '비밀번호 재설정에 실패했습니다.'
            };
        }
    }

    // ====================================================================
    // 유틸리티 함수
    // ====================================================================

    /**
     * 에러 메시지 추출
     */
    extractErrorMessage(response) {
        if (response.errors) {
            // Laravel validation errors
            const errorKeys = Object.keys(response.errors);
            if (errorKeys.length > 0) {
                return response.errors[errorKeys[0]][0];
            }
        }
        return response.message || '요청 처리 중 오류가 발생했습니다.';
    }

    /**
     * 401 에러 처리
     */
    handleUnauthorized() {
        console.warn('인증되지 않은 접근으로 인한 자동 로그아웃');
        this.handleSessionExpired('인증이 필요합니다. 로그인 페이지로 이동합니다.');
    }

    /**
     * 세션 만료 처리
     */
    handleSessionExpired(message = '세션이 만료되었습니다. 다시 로그인해주세요.') {
        this.removeToken();
        if (confirm(message)) {
            window.location.href = '/login';
        } else {
            window.location.href = '/login';
        }
    }

    // ====================================================================
    // 이벤트 시스템
    // ====================================================================

    /**
     * 이벤트 리스너 추가
     */
    on(event, callback) {
        if (!this.listeners[event]) {
            this.listeners[event] = [];
        }
        this.listeners[event].push(callback);
    }

    /**
     * 이벤트 리스너 제거
     */
    off(event, callback) {
        if (this.listeners[event]) {
            this.listeners[event] = this.listeners[event].filter(cb => cb !== callback);
        }
    }

    /**
     * 이벤트 발생
     */
    emit(event, data) {
        if (this.listeners[event]) {
            this.listeners[event].forEach(callback => callback(data));
        }
    }

    // ====================================================================
    // 유효성 검사 함수
    // ====================================================================

    /**
     * 이메일 유효성 검사
     */
    validateEmail(email) {
        if (!email) {
            return { valid: false, message: '이메일을 입력해주세요.' };
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return { valid: false, message: '올바른 이메일 형식을 입력해주세요.' };
        }
        
        return { valid: true, message: null };
    }

    /**
     * 비밀번호 유효성 검사
     */
    validatePassword(password) {
        if (!password) {
            return { valid: false, message: '비밀번호를 입력해주세요.' };
        }
        
        if (password.length < 6) {
            return { valid: false, message: '비밀번호는 최소 6자 이상이어야 합니다.' };
        }
        
        return { valid: true, message: null };
    }

    /**
     * 로그인 폼 유효성 검사
     */
    validateLoginForm(email, password) {
        const emailValidation = this.validateEmail(email);
        if (!emailValidation.valid) return emailValidation;
        
        const passwordValidation = this.validatePassword(password);
        if (!passwordValidation.valid) return passwordValidation;
        
        return { valid: true, message: null };
    }

    /**
     * 회원가입 폼 유효성 검사
     */
    validateSignupForm(userData) {
        const { name, email, password, password_confirmation } = userData;

        // 이름 검사
        if (!name || name.trim().length < 2) {
            return { valid: false, message: '이름은 최소 2자 이상 입력해주세요.' };
        }

        // 이메일 검사
        const emailValidation = this.validateEmail(email);
        if (!emailValidation.valid) return emailValidation;

        // 비밀번호 검사
        const passwordValidation = this.validatePassword(password);
        if (!passwordValidation.valid) return passwordValidation;

        // 비밀번호 확인 검사
        if (password !== password_confirmation) {
            return { valid: false, message: '비밀번호가 일치하지 않습니다.' };
        }

        return { valid: true, message: null };
    }

    /**
     * 비밀번호 재설정 폼 유효성 검사
     */
    validateResetPasswordForm(resetData) {
        const { token, email, password, password_confirmation } = resetData;

        if (!token) {
            return { valid: false, message: '유효하지 않은 재설정 토큰입니다.' };
        }

        const emailValidation = this.validateEmail(email);
        if (!emailValidation.valid) return emailValidation;

        const passwordValidation = this.validatePassword(password);
        if (!passwordValidation.valid) return passwordValidation;

        if (password !== password_confirmation) {
            return { valid: false, message: '비밀번호가 일치하지 않습니다.' };
        }

        return { valid: true, message: null };
    }
}

// 전역 AuthManager 인스턴스 생성
window.AuthManager = new AuthManager();

console.log('중앙집중식 AuthManager 로드 완료');
</script>