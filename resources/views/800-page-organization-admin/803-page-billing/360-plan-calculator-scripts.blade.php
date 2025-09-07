<script>
document.addEventListener('DOMContentLoaded', function() {
    // 플랜 정보
    const planPricing = {
        basic: { monthly: 10000, name: 'Basic' },
        pro: { monthly: 20000, name: 'Pro' },
        enterprise: { monthly: 50000, name: 'Enterprise' }
    };

    // DOM 요소들
    const licenseCountInput = document.getElementById('licenseCount');
    const decreaseBtn = document.getElementById('decreaseLicense');
    const increaseBtn = document.getElementById('increaseLicense');
    const planOptions = document.querySelectorAll('.plan-option');
    const billingCycleInputs = document.querySelectorAll('input[name="billingCycle"]');
    const applyPlanBtn = document.getElementById('applyPlan');

    // 현재 선택된 값들
    let currentPlan = 'basic';
    let currentLicenseCount = 1;
    let currentBillingCycle = 'monthly';

    // 초기화
    updateDisplay();
    selectPlan('basic');

    // 이벤트 리스너들
    decreaseBtn.addEventListener('click', function() {
        const currentValue = parseInt(licenseCountInput.value);
        if (currentValue > 1) {
            licenseCountInput.value = currentValue - 1;
            updateLicenseCount();
        }
    });

    increaseBtn.addEventListener('click', function() {
        const currentValue = parseInt(licenseCountInput.value);
        if (currentValue < 10000) {
            licenseCountInput.value = currentValue + 1;
            updateLicenseCount();
        }
    });

    licenseCountInput.addEventListener('input', function() {
        let value = parseInt(this.value);
        if (isNaN(value) || value < 1) {
            value = 1;
        } else if (value > 10000) {
            value = 10000;
        }
        this.value = value;
        updateLicenseCount();
    });

    planOptions.forEach(option => {
        option.addEventListener('click', function() {
            const plan = this.dataset.plan;
            selectPlan(plan);
        });
    });

    billingCycleInputs.forEach(input => {
        input.addEventListener('change', function() {
            currentBillingCycle = this.value;
            updateDisplay();
        });
    });

    applyPlanBtn.addEventListener('click', function() {
        applySelectedPlan();
    });

    // 함수들
    function updateLicenseCount() {
        currentLicenseCount = parseInt(licenseCountInput.value);
        updateDisplay();
    }

    function selectPlan(plan) {
        currentPlan = plan;
        
        // UI 업데이트
        planOptions.forEach(option => {
            if (option.dataset.plan === plan) {
                option.classList.add('border-blue-500', 'bg-blue-50');
                option.classList.remove('border-gray-200');
            } else {
                option.classList.remove('border-blue-500', 'bg-blue-50');
                option.classList.add('border-gray-200');
            }
        });

        updateDisplay();
    }

    function updateDisplay() {
        const planInfo = planPricing[currentPlan];
        const monthlyPrice = planInfo.monthly;
        const yearlyPrice = Math.round(monthlyPrice * 12 * 0.9); // 10% 할인
        
        // 기본 정보 업데이트
        document.getElementById('selectedPlan').textContent = planInfo.name;
        document.getElementById('displayLicenseCount').textContent = currentLicenseCount.toLocaleString();
        
        let subtotal, total, priceText, billingNote;
        
        if (currentBillingCycle === 'monthly') {
            subtotal = monthlyPrice * currentLicenseCount;
            total = subtotal;
            priceText = `₩${monthlyPrice.toLocaleString()}`;
            billingNote = '매월 청구됩니다';
            document.getElementById('discountRow').style.display = 'none';
        } else {
            const yearlySubtotal = monthlyPrice * 12 * currentLicenseCount;
            const discountAmount = yearlySubtotal * 0.1;
            subtotal = yearlySubtotal;
            total = yearlySubtotal - discountAmount;
            priceText = `₩${yearlyPrice.toLocaleString()}`;
            billingNote = '연간 청구됩니다 (10% 할인 적용)';
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('discountAmount').textContent = `-₩${Math.round(discountAmount).toLocaleString()}`;
        }

        document.getElementById('pricePerLicense').textContent = priceText;
        document.getElementById('displayBillingCycle').textContent = currentBillingCycle === 'monthly' ? '월간' : '연간';
        document.getElementById('subtotal').textContent = `₩${Math.round(subtotal).toLocaleString()}`;
        document.getElementById('totalAmount').textContent = `₩${Math.round(total).toLocaleString()}`;
        document.getElementById('billingNote').textContent = billingNote;
    }

    function applySelectedPlan() {
        // 토스페이먼츠 초기화 (테스트 키)
        const clientKey = 'test_ck_D5GePWvyJnrK0W0k6q8gLzN97Eoq';
        const tossPayments = TossPayments(clientKey);
        
        // 결제 정보 준비
        const totalPrice = Math.round(getTotalAmount());
        const planInfo = planPricing[currentPlan];
        const orderId = generateOrderId();
        const orderName = `${planInfo.name} 플랜 (${currentLicenseCount}개 라이센스)`;
        
        console.log('Payment Info:', {
            totalPrice,
            orderId,
            orderName,
            plan: currentPlan,
            licenses: currentLicenseCount,
            billingCycle: currentBillingCycle
        });

        // 로딩 표시
        applyPlanBtn.disabled = true;
        applyPlanBtn.textContent = '결제 창 열기 중...';

        try {
            // 토스페이먼츠 결제창 호출
            tossPayments.requestPayment('카드', {
                amount: totalPrice,
                orderId: orderId,
                orderName: orderName,
                customerName: '사용자',
                customerEmail: 'user@example.com',
                successUrl: `${window.location.origin}/organizations/${getOrganizationId()}/admin/billing/payment-success`,
                failUrl: `${window.location.origin}/organizations/${getOrganizationId()}/admin/billing/payment-fail`,
                // 결제 데이터를 success URL로 전달
                metadata: {
                    plan: currentPlan,
                    licenses: currentLicenseCount.toString(),
                    billing_cycle: currentBillingCycle,
                    organization_id: getOrganizationId()
                }
            });
        } catch (error) {
            console.error('Payment Error:', error);
            alert('결제창을 열 수 없습니다. 잠시 후 다시 시도해주세요.');
        } finally {
            // 로딩 해제
            applyPlanBtn.disabled = false;
            applyPlanBtn.textContent = '이 플랜으로 변경';
        }
    }

    // 총 금액 계산 함수
    function getTotalAmount() {
        const planInfo = planPricing[currentPlan];
        const monthlyPrice = planInfo.monthly;
        const licensePrice = monthlyPrice * currentLicenseCount;
        
        if (currentBillingCycle === 'yearly') {
            return licensePrice * 12 * 0.9; // 10% 할인
        } else {
            return licensePrice;
        }
    }

    // 주문 ID 생성
    function generateOrderId() {
        const timestamp = Date.now();
        const random = Math.random().toString(36).substring(2, 8);
        return `plan_${timestamp}_${random}`;
    }

    function getOrganizationId() {
        // URL에서 조직 ID 추출
        const path = window.location.pathname;
        const matches = path.match(/\/organizations\/(\d+)/);
        return matches ? matches[1] : null;
    }
});
</script>