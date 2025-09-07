<script>
document.addEventListener('DOMContentLoaded', function() {
    // URL 파라미터에서 결제 정보 추출
    const urlParams = new URLSearchParams(window.location.search);
    const paymentKey = urlParams.get('paymentKey');
    const orderId = urlParams.get('orderId');
    const amount = urlParams.get('amount');

    console.log('Payment Success - URL Params:', { paymentKey, orderId, amount });

    // DOM 요소들
    const loadingDiv = document.getElementById('loading');
    const successDiv = document.getElementById('success');
    const errorDiv = document.getElementById('error');
    const downloadReceiptBtn = document.getElementById('downloadReceipt');

    // 결제 정보 표시
    if (paymentKey) {
        document.getElementById('paymentKey').textContent = paymentKey;
    }
    if (orderId) {
        document.getElementById('orderId').textContent = orderId;
    }
    if (amount) {
        document.getElementById('totalAmount').textContent = `₩${parseInt(amount).toLocaleString()}`;
    }

    // 토스페이먼츠에서 결제 상세 정보 조회
    if (paymentKey && orderId && amount) {
        verifyPayment(paymentKey, orderId, amount);
    } else {
        showError('결제 정보가 누락되었습니다.');
    }

    // 영수증 다운로드 버튼 이벤트
    downloadReceiptBtn.addEventListener('click', function() {
        if (paymentKey) {
            downloadReceipt(paymentKey);
        }
    });

    async function verifyPayment(paymentKey, orderId, amount) {
        try {
            // 토스페이먼츠 결제 승인 API 호출
            const response = await fetch('/api/organizations/' + getOrganizationId() + '/billing/verify-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    paymentKey: paymentKey,
                    orderId: orderId,
                    amount: amount
                })
            });

            const data = await response.json();
            console.log('Payment Verification Result:', data);

            if (data.success && data.payment) {
                showPaymentDetails(data.payment);
                showSuccess();
            } else {
                showError(data.message || '결제 검증에 실패했습니다.');
            }

        } catch (error) {
            console.error('Payment verification error:', error);
            showError('결제 검증 중 오류가 발생했습니다.');
        }
    }

    function showPaymentDetails(payment) {
        // 플랜 정보 표시
        const planMapping = {
            basic: 'Basic',
            pro: 'Pro',
            enterprise: 'Enterprise'
        };

        // 결제 정보에서 메타데이터 추출
        const metadata = payment.metadata || {};
        const plan = metadata.plan || 'Unknown';
        const licenses = metadata.licenses || '-';
        const billingCycle = metadata.billing_cycle || '-';

        document.getElementById('planName').textContent = planMapping[plan] || plan;
        document.getElementById('licenseCount').textContent = licenses + '개';
        document.getElementById('billingCycle').textContent = billingCycle === 'monthly' ? '월간' : '연간';

        // 결제 방법 표시
        const method = payment.method || 'Unknown';
        const card = payment.card || {};
        let paymentMethodText = method;
        if (card.company) {
            paymentMethodText += ` (${card.company})`;
        }
        document.getElementById('paymentMethod').textContent = paymentMethodText;

        // 결제 일시 표시
        const approvedAt = payment.approvedAt;
        if (approvedAt) {
            const date = new Date(approvedAt);
            document.getElementById('approvedAt').textContent = date.toLocaleString('ko-KR');
        }
    }

    function showSuccess() {
        loadingDiv.classList.add('hidden');
        successDiv.classList.remove('hidden');
        errorDiv.classList.add('hidden');
    }

    function showError(message) {
        loadingDiv.classList.add('hidden');
        successDiv.classList.add('hidden');
        errorDiv.classList.remove('hidden');
        
        const errorText = errorDiv.querySelector('p');
        if (errorText) {
            errorText.textContent = message;
        }
    }

    async function downloadReceipt(paymentKey) {
        try {
            const response = await fetch(`/api/organizations/${getOrganizationId()}/billing/download-receipt`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    paymentKey: paymentKey
                })
            });

            if (response.ok) {
                // 파일 다운로드
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `receipt_${paymentKey}.pdf`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            } else {
                alert('영수증 다운로드에 실패했습니다.');
            }
        } catch (error) {
            console.error('Receipt download error:', error);
            alert('영수증 다운로드 중 오류가 발생했습니다.');
        }
    }

    function getOrganizationId() {
        // URL에서 조직 ID 추출
        const path = window.location.pathname;
        const matches = path.match(/\/organizations\/(\d+)/);
        return matches ? matches[1] : null;
    }
});
</script>