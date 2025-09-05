{{-- 휴대폰 번호 검증 기능 --}}
<script>
// 전화번호 플레이스홀더 업데이트 함수
function updatePhonePlaceholder() {
    const phoneField = document.getElementById('phone_number');
    const countryCodeField = document.getElementById('country_code');
    
    if (!phoneField || !countryCodeField) return;
    
    const countryCode = countryCodeField.value;
    const selectedOption = countryCodeField.querySelector(`option[value="${countryCode}"]`);
    
    if (selectedOption && selectedOption.dataset.example) {
        // API에서 받은 예제 사용
        phoneField.placeholder = selectedOption.dataset.example.replace(/[\s\-\(\)]/g, '');
    } else {
        // 기본 예제들 (fallback)
        switch (countryCode) {
            case '+82': // 한국
                phoneField.placeholder = '01012345678';
                break;
            case '+1': // 미국
                phoneField.placeholder = '2125551234';
                break;
            case '+44': // 영국
                phoneField.placeholder = '2012345678';
                break;
            case '+81': // 일본
                phoneField.placeholder = '09012345678';
                break;
            case '+86': // 중국
                phoneField.placeholder = '13912345678';
                break;
            case '+33': // 프랑스
                phoneField.placeholder = '612345678';
                break;
            case '+49': // 독일
                phoneField.placeholder = '15123456789';
                break;
            default:
                phoneField.placeholder = '전화번호 입력';
        }
    }
}

// 전화번호 검증 함수
function validatePhone() {
    const phoneField = document.getElementById('phone_number');
    const countryCodeField = document.getElementById('country_code');
    
    if (!phoneField || !countryCodeField) return;
    
    const phone = phoneField.value.trim();
    const countryCode = countryCodeField.value;
    const errorElement = document.getElementById('phoneError');
    
    if (errorElement) {
        if (phone) {
            // 전화번호는 숫자만 허용하는 기본 검증 (더 정확한 검증은 서버에서)
            let isValid = /^\d{7,15}$/.test(phone);
            let errorMessage = '전화번호는 7-15자리 숫자로 입력해주세요.';
            
            // 주요 국가별 간단한 검증
            if (isValid) {
                switch (countryCode) {
                    case '+82': // 한국
                        if (phone.startsWith('0')) {
                            isValid = /^0\d{8,10}$/.test(phone);
                            errorMessage = '한국 전화번호 형식이 올바르지 않습니다.';
                        }
                        break;
                    case '+1': // 미국/캐나다
                        isValid = /^\d{10}$/.test(phone);
                        errorMessage = '미국/캐나다 전화번호는 10자리 숫자로 입력해주세요.';
                        break;
                    case '+86': // 중국
                        isValid = /^\d{11}$/.test(phone);
                        errorMessage = '중국 전화번호는 11자리 숫자로 입력해주세요.';
                        break;
                }
            }
            
            if (!isValid) {
                errorElement.textContent = errorMessage;
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
            }
        } else {
            errorElement.classList.add('hidden');
        }
    }
}

// 휴대폰 번호 필드 검증 설정
function setupPhoneValidation() {
    const phoneField = document.getElementById('phone_number');
    const countryCodeField = document.getElementById('country_code');
    
    if (phoneField) {
        phoneField.addEventListener('blur', validatePhone);
        
        if (countryCodeField) {
            countryCodeField.addEventListener('change', () => {
                updatePhonePlaceholder();
                validatePhone();
            });
            // 초기 플레이스홀더 설정
            updatePhonePlaceholder();
        }
    }
}
</script>