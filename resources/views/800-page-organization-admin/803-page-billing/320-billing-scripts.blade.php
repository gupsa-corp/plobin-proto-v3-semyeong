    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 전역 변수
            let currentBillingData = null;
            let currentOrganizationId = {{ $id ?? 'null' }};

            // DOM 요소들
            const subscriptionCard = document.getElementById('subscription-card');
            const noSubscriptionCard = document.getElementById('no-subscription-card');
            const addPaymentMethodBtn = document.getElementById('addPaymentMethodBtn');
            const paymentMethodsList = document.getElementById('paymentMethodsList');

            // 페이지 로드 시 결제 데이터 가져오기
            loadBillingData();

            // 이벤트 리스너
            if (addPaymentMethodBtn) {
                addPaymentMethodBtn.addEventListener('click', showPaymentMethodModal);
            }

            // 모달 닫기 이벤트 리스너들
            const closePaymentModal = document.getElementById('closePaymentModal');
            const cancelPaymentMethod = document.getElementById('cancelPaymentMethod');
            const closeBillingDetailModal = document.getElementById('closeBillingDetailModal');
            const closePlanModal = document.getElementById('closePlanModal');
            const cancelPlanChange = document.getElementById('cancelPlanChange');

            if (closePaymentModal) closePaymentModal.addEventListener('click', hidePaymentMethodModal);
            if (cancelPaymentMethod) cancelPaymentMethod.addEventListener('click', hidePaymentMethodModal);
            if (closeBillingDetailModal) closeBillingDetailModal.addEventListener('click', hideBillingDetailModal);
            if (closePlanModal) closePlanModal.addEventListener('click', hidePlanChangeModal);
            if (cancelPlanChange) cancelPlanChange.addEventListener('click', hidePlanChangeModal);

            // 모달 백드롭 클릭 시 닫기
            const paymentMethodModal = document.getElementById('paymentMethodModal');
            const billingDetailModal = document.getElementById('billingDetailModal');
            const planChangeModal = document.getElementById('planChangeModal');

            if (paymentMethodModal) {
                paymentMethodModal.addEventListener('click', function(e) {
                    if (e.target === paymentMethodModal) {
                        hidePaymentMethodModal();
                    }
                });
            }

            if (billingDetailModal) {
                billingDetailModal.addEventListener('click', function(e) {
                    if (e.target === billingDetailModal) {
                        hideBillingDetailModal();
                    }
                });
            }

            if (planChangeModal) {
                planChangeModal.addEventListener('click', function(e) {
                    if (e.target === planChangeModal) {
                        hidePlanChangeModal();
                    }
                });
            }

            // ESC 키로 모달 닫기
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // 현재 열려있는 모달 찾아서 닫기
                    if (paymentMethodModal && paymentMethodModal.style.display === 'flex') {
                        hidePaymentMethodModal();
                    }
                    if (billingDetailModal && billingDetailModal.style.display === 'flex') {
                        hideBillingDetailModal();
                    }
                    if (planChangeModal && planChangeModal.style.display === 'flex') {
                        hidePlanChangeModal();
                    }
                }
            });

            /**
             * 결제 데이터 로드
             */
            async function loadBillingData() {
                if (!currentOrganizationId) return;

                try {
                    const response = await fetch(`/api/organizations/${currentOrganizationId}/billing/data`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    currentBillingData = data.data;

                    updateUI(data.data);
                    updatePaymentMethods(data.data.payment_methods || []);

                } catch (error) {
                    console.error('결제 데이터 로드 실패:', error);
                    showNoSubscription();
                    showNoPaymentMethods();
                }
            }

            /**
             * UI 업데이트
             */
            function updateUI(data) {
                if (data.subscription && data.subscription.is_active) {
                    showSubscription(data.subscription);
                } else {
                    showNoSubscription();
                }
            }

            /**
             * 구독 정보 표시
             */
            function showSubscription(subscription) {
                if (!subscriptionCard) return;
                
                const planNameEl = document.getElementById('plan-name');
                const planStatusEl = document.getElementById('plan-status');
                const nextBillingDateEl = document.getElementById('next-billing-date');
                const currentMembersEl = document.getElementById('current-members');

                if (planNameEl) planNameEl.textContent = subscription.plan_name || '';
                if (planStatusEl) planStatusEl.textContent = subscription.status_text || '';
                if (nextBillingDateEl) nextBillingDateEl.textContent = subscription.next_billing_date || '';
                if (currentMembersEl) currentMembersEl.textContent = `${subscription.current_members || 0}/${subscription.max_members || 0}명`;

                subscriptionCard.style.display = 'block';
                if (noSubscriptionCard) noSubscriptionCard.style.display = 'none';
            }

            /**
             * 구독 없음 상태 표시
             */
            function showNoSubscription() {
                if (subscriptionCard) subscriptionCard.style.display = 'none';
                if (noSubscriptionCard) noSubscriptionCard.style.display = 'block';
            }

            /**
             * 결제 수단 목록 업데이트
             */
            function updatePaymentMethods(paymentMethods) {
                if (!paymentMethodsList) return;

                if (!paymentMethods || paymentMethods.length === 0) {
                    showNoPaymentMethods();
                    return;
                }

                let html = '';
                paymentMethods.forEach((method, index) => {
                    const priority = index + 1;
                    const priorityColor = priority === 1 ? 'green' : (priority === 2 ? 'orange' : 'gray');
                    
                    html += `
                        <div class="payment-method-item flex items-center justify-between p-4 border border-gray-200 rounded-lg ${priority === 1 ? 'bg-green-50 border-green-200' : ''}" data-method-id="${method.id}" data-priority="${priority}">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-${priorityColor}-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">${priority}</span>
                                    </div>
                                </div>
                                <div class="w-12 h-8 bg-${method.card_company === 'VISA' ? 'blue' : 'red'}-600 rounded flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">${method.card_company === 'VISA' ? 'V' : (method.card_company === 'Mastercard' ? 'MC' : method.card_company.charAt(0))}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">${method.card_number}</p>
                                    <p class="text-sm text-gray-500">만료일: ${method.expiry_date}</p>
                                </div>
                                <div class="flex gap-2">
                                    <span class="px-2.5 py-0.5 bg-${priorityColor}-100 text-${priorityColor}-800 text-xs font-medium rounded-full">${priority}순위</span>
                                    ${method.is_default ? '<span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">기본</span>' : ''}
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="editPaymentMethod('${method.id}')" class="text-blue-600 hover:text-blue-800 text-sm">편집</button>
                                ${!method.is_default ? `<button onclick="setDefaultPaymentMethod('${method.id}')" class="text-green-600 hover:text-green-800 text-sm">기본으로 설정</button>` : ''}
                                <button onclick="deletePaymentMethod('${method.id}')" class="text-red-600 hover:text-red-800 text-sm">삭제</button>
                            </div>
                        </div>
                    `;
                });

                paymentMethodsList.innerHTML = html;
            }

            /**
             * 결제 수단 없음 상태 표시
             */
            function showNoPaymentMethods() {
                if (!paymentMethodsList) return;

                const noPaymentEl = document.getElementById('no-payment-methods');
                if (noPaymentEl) {
                    noPaymentEl.style.display = 'block';
                }
            }

            /**
             * 결제 수단 추가 모달 표시
             */
            function showPaymentMethodModal() {
                const modal = document.getElementById('paymentMethodModal');
                if (modal) {
                    modal.style.display = 'flex';
                }
            }

            /**
             * 결제 수단 모달 닫기
             */
            function hidePaymentMethodModal() {
                const modal = document.getElementById('paymentMethodModal');
                if (modal) {
                    modal.style.display = 'none';
                    // 폼 초기화
                    const form = document.getElementById('paymentMethodForm');
                    if (form) form.reset();
                }
            }

            /**
             * 결제 내역 상세 모달 닫기
             */
            function hideBillingDetailModal() {
                const modal = document.getElementById('billingDetailModal');
                if (modal) {
                    modal.style.display = 'none';
                }
            }

            /**
             * 플랜 변경 모달 닫기
             */
            function hidePlanChangeModal() {
                const modal = document.getElementById('planChangeModal');
                if (modal) {
                    modal.style.display = 'none';
                }
            }

            /**
             * 결제 수단 편집
             */
            window.editPaymentMethod = function(paymentMethodId) {
                if (!paymentMethodId) {
                    showError('결제 수단 ID가 필요합니다.');
                    return;
                }

                // 실제 결제 수단 편집 모달 표시
                showPaymentMethodEditModal(paymentMethodId);
            };

            /**
             * 결제 수단 삭제
             */
            window.deletePaymentMethod = async function(paymentMethodId) {
                if (!paymentMethodId) {
                    showError('결제 수단 ID가 필요합니다.');
                    return;
                }

                if (!confirm('정말로 이 결제 수단을 삭제하시겠습니까?')) {
                    return;
                }
                
                try {
                    const response = await fetch(`/api/organizations/${currentOrganizationId}/payment-methods/${paymentMethodId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        showError(errorData.message || '결제 수단 삭제에 실패했습니다.');
                        return;
                    }

                    showSuccess('결제 수단이 성공적으로 삭제되었습니다.');
                    loadBillingData(); // 데이터 새로고침

                } catch (error) {
                    console.error('결제 수단 삭제 실패:', error);
                    showError('결제 수단 삭제 중 오류가 발생했습니다.');
                }
            };

            /**
             * 기본 결제 수단 설정
             */
            window.setDefaultPaymentMethod = async function(paymentMethodId) {
                if (!paymentMethodId) {
                    showError('결제 수단 ID가 필요합니다.');
                    return;
                }

                try {
                    const response = await fetch(`/api/organizations/${currentOrganizationId}/payment-methods/${paymentMethodId}/set-default`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        showError(errorData.message || '기본 결제 수단 설정에 실패했습니다.');
                        return;
                    }

                    showSuccess('기본 결제 수단이 변경되었습니다.');
                    loadBillingData(); // 데이터 새로고침

                } catch (error) {
                    console.error('기본 결제 수단 설정 실패:', error);
                    showError('기본 결제 수단 설정 중 오류가 발생했습니다.');
                }
            };

            /**
             * 결제 내역 상세보기
             */
            window.showBillingDetail = async function(billingHistoryId) {
                if (!billingHistoryId) {
                    showError('결제 내역 ID가 필요합니다.');
                    return;
                }

                const modal = document.getElementById('billingDetailModal');
                if (!modal) return;

                try {
                    const response = await fetch(`/api/organizations/${currentOrganizationId}/billing-histories/${billingHistoryId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        showError(errorData.message || '결제 내역 정보를 불러올 수 없습니다.');
                        return;
                    }

                    const billingDetail = await response.json();
                    const detail = billingDetail.data;

                    if (!detail) {
                        showError('결제 내역을 찾을 수 없습니다.');
                        return;
                    }

                    // 모달 데이터 설정
                    document.getElementById('detailDate').textContent = detail.date || '';
                    document.getElementById('detailStatus').textContent = detail.status_text || '';
                    document.getElementById('detailAmount').textContent = detail.formatted_amount || '';
                    document.getElementById('detailPaymentId').textContent = detail.payment_id || '';
                    document.getElementById('detailPlanName').textContent = detail.plan_name || '';
                    document.getElementById('detailPeriod').textContent = detail.period || '';
                    document.getElementById('detailBillingCycle').textContent = detail.billing_cycle || '';
                    
                    // 카드 정보
                    if (detail.card_number) {
                        document.getElementById('detailCardNumber').textContent = detail.card_number;
                        document.getElementById('detailCardInfo').textContent = `${detail.card_company} · 만료일: ${detail.expiry_date}`;
                    }

                    // 모달 표시
                    modal.style.display = 'flex';

                } catch (error) {
                    console.error('결제 내역 로드 실패:', error);
                    showError('결제 내역을 불러오는 중 오류가 발생했습니다.');
                }
            };

            /**
             * 성공 메시지 표시
             */
            function showSuccess(message) {
                // 성공 알림 표시
                console.log('Success:', message);
                alert(message);
            }

            /**
             * 에러 메시지 표시
             */
            function showError(message) {
                // 에러 알림 표시
                console.error('Error:', message);
                alert(message);
            }

            /**
             * 결제 수단 편집 모달 표시
             */
            function showPaymentMethodEditModal(paymentMethodId) {
                // 구현 필요
                console.log('결제 수단 편집:', paymentMethodId);
            }
        });
    </script>