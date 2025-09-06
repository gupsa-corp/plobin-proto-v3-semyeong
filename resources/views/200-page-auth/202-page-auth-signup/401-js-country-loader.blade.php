{{-- 국가 목록 로드 기능 --}}
<script>
// 기본 국가 목록 (API 실패 시 fallback)
const defaultCountries = [
    { country_code: '+82', nickname: '한국 (+82)', example: '010-1234-5678' },
    { country_code: '+1', nickname: '미국 (+1)', example: '(212) 555-1234' },
    { country_code: '+44', nickname: '영국 (+44)', example: '020 1234 5678' },
    { country_code: '+81', nickname: '일본 (+81)', example: '090-1234-5678' },
    { country_code: '+86', nickname: '중국 (+86)', example: '139 1234 5678' },
    { country_code: '+33', nickname: '프랑스 (+33)', example: '06 12 34 56 78' },
    { country_code: '+49', nickname: '독일 (+49)', example: '0151 23456789' }
];

// 국가 목록 로드
async function loadCountries() {
    if (countriesLoaded) return;

    const countrySelect = document.getElementById('country_code');
    if (!countrySelect) return;

    // 현재 선택된 값 저장
    const currentValue = countrySelect.value;
    let countriesToUse = defaultCountries; // 기본값으로 시작

    try {
        const response = await fetch('/api/countries');
        const result = await response.json();

        if (response.ok && result.success && result.data && result.data.length > 0) {
            countriesToUse = result.data;
            console.log('API에서 국가 목록 로드 성공');
        } else {
            console.warn('API 국가 목록 로드 실패, 기본 목록 사용:', result?.message || '데이터 없음');
        }
    } catch (error) {
        console.warn('국가 목록 API 호출 실패, 기본 목록 사용:', error.message);
    }

    // 모든 기존 옵션들 제거
    countrySelect.innerHTML = '';

    // 국가들 추가
    countriesToUse.forEach(country => {
        const option = document.createElement('option');
        option.value = country.country_code;
        option.textContent = country.display_name;
        option.dataset.example = country.example || '';
        countrySelect.appendChild(option);
    });

    // 이전에 선택된 값이 있으면 복원, 없으면 기본값(+82) 선택
    if (currentValue && countrySelect.querySelector(`option[value="${currentValue}"]`)) {
        countrySelect.value = currentValue;
    } else {
        countrySelect.value = '+82'; // 한국을 기본값으로
    }

    // 전화번호 플레이스홀더 업데이트
    const phoneField = document.getElementById('phone_number');
    if (phoneField) {
        const updatePhonePlaceholder = () => {
            const countryCode = countrySelect.value;
            const selectedOption = countrySelect.querySelector(`option[value="${countryCode}"]`);

            if (selectedOption && selectedOption.dataset.example) {
                phoneField.placeholder = selectedOption.dataset.example.replace(/[\s\-\(\)]/g, '');
            }
        };
        updatePhonePlaceholder();
    }

    countriesLoaded = true;
    console.log('국가 목록 로드 완료:', countriesToUse.length + '개 국가');
}
</script>
