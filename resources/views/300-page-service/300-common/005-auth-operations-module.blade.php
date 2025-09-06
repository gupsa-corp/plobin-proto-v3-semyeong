{{-- 인증 작업 모듈 --}}
<script>
/**
 * 인증 작업 모듈
 * 로그인, 로그아웃, 회원가입, 비밀번호 관련 기능
 */
class AuthOperationsModule {
    constructor(authManager) {
        this.authManager = authManager;
    }

    /**
     * 로그인 처리
     */
    async login(email, password, remember = false) {
        try {
            const response = await this.authManager.httpModule.makeRequest('/api/auth/login', {
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
                    this.authManager.tokenModule.setToken(token);
                }

                // 사용자 정보 저장
                const user = response.user || (response.data && response.data.user);
                if (user) {
                    this.authManager.userModule.setUser(user);
                }

                this.authManager.emit('loginSuccess', { user, token, response });

                return {
                    success: true,
                    data: response,
                    redirectUrl: response.redirect_url || (response.data && response.data.redirect_url) || '/dashboard'
                };
            } else {
                const errorMsg = this.authManager.utilityModule.extractErrorMessage(response);
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
            await this.authManager.httpModule.makeRequest('/api/auth/logout', {
                method: 'POST'
            });
        } catch (error) {
            console.warn('로그아웃 요청 실패:', error);
        } finally {
            // 응답과 상관없이 클라이언트 토큰 제거
            this.authManager.tokenModule.removeToken();
            this.authManager.emit('logoutSuccess');
            window.location.href = redirectUrl;
        }
    }

    /**
     * 회원가입 처리 (참고용 - 실제로는 사용하지 않음)
     */
    async signup(userData) {
        try {
            const response = await this.authManager.httpModule.makeRequest('/api/auth/signup', {
                method: 'POST',
                body: JSON.stringify(userData)
            });

            if (response.success) {
                // 토큰이 있다면 저장
                if (response.data && response.data.token) {
                    this.authManager.tokenModule.setToken(response.data.token);
                    this.authManager.userModule.setUser(response.data.user);
                }

                this.authManager.emit('signupSuccess', response);

                return {
                    success: true,
                    data: response.data,
                    message: response.message || '회원가입이 완료되었습니다.',
                    redirectUrl: response.redirect_url || '/dashboard'
                };
            } else {
                const errorMsg = this.authManager.utilityModule.extractErrorMessage(response);
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
            const response = await this.authManager.httpModule.makeRequest('/api/auth/forgot-password', {
                method: 'POST',
                body: JSON.stringify({ email })
            });

            if (response.success) {
                return {
                    success: true,
                    message: response.message || '비밀번호 재설정 링크가 이메일로 전송되었습니다.'
                };
            } else {
                const errorMsg = this.authManager.utilityModule.extractErrorMessage(response);
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
            const response = await this.authManager.httpModule.makeRequest('/api/auth/reset-password', {
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
                const errorMsg = this.authManager.utilityModule.extractErrorMessage(response);
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
}

window.AuthOperationsModule = AuthOperationsModule;
</script>