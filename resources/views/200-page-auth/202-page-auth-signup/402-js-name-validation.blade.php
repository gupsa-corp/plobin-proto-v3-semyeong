{{-- 이름 필드 검증 기능 --}}
<script>
// 성 필드 검증 설정
function setupFirstNameValidation() {
    const firstNameField = document.getElementById('first_name');
    if (firstNameField) {
        firstNameField.addEventListener('blur', function() {
            const firstName = this.value.trim();
            const errorElement = document.getElementById('firstNameError');
            
            if (errorElement) {
                if (firstName && firstName.length > 0 && !/^[가-힣a-zA-Z\s]+$/.test(firstName)) {
                    errorElement.textContent = '성은 한글, 영문만 입력 가능합니다.';
                    errorElement.classList.remove('hidden');
                } else {
                    errorElement.classList.add('hidden');
                }
            }
        });
    }
}

// 이름 필드 검증 설정
function setupLastNameValidation() {
    const lastNameField = document.getElementById('last_name');
    if (lastNameField) {
        lastNameField.addEventListener('blur', function() {
            const lastName = this.value.trim();
            const errorElement = document.getElementById('lastNameError');
            
            if (errorElement) {
                if (lastName && lastName.length > 0 && !/^[가-힣a-zA-Z\s]+$/.test(lastName)) {
                    errorElement.textContent = '이름은 한글, 영문만 입력 가능합니다.';
                    errorElement.classList.remove('hidden');
                } else {
                    errorElement.classList.add('hidden');
                }
            }
        });
    }
}
</script>