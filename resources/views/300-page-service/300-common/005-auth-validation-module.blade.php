{{-- 유효성 검사 모듈 --}}
<script>
/**
 * 유효성 검사 모듈
 * 폼 검증 및 데이터 유효성 검사
 */
class AuthValidationModule {
    constructor(authManager) {
        this.authManager = authManager;
    }

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

window.AuthValidationModule = AuthValidationModule;
</script>