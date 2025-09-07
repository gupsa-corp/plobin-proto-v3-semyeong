    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 전역 변수
            let currentBillingData = null;
            let currentOrganizationId = {{ $id ?? 'null' }};

            // DOM 요소들 (안전하게 가져오기)
            const subscriptionCard = document.getElementById('subscription-card');
            const noSubscriptionCard = document.getElementById('no-subscription-card');
            const downloadReceiptBtn = document.getElementById('download-receipt-btn');
            const registerBusinessBtn = document.getElementById('register-business-btn');
            const businessInfoModal = document.getElementById('business-info-modal');
            const businessInfoForm = document.getElementById('business-info-form');
            const addPaymentMethodBtn = document.getElementById('addPaymentMethodBtn');
            const paymentMethodModal = document.getElementById('paymentMethodModal');
            const paymentMethodForm = document.getElementById('paymentMethodForm');
            const closePaymentModal = document.getElementById('closePaymentModal');
            const cancelPaymentMethod = document.getElementById('cancelPaymentMethod');
            const changePlanBtn = document.getElementById('change-plan-btn');
            const planChangeModal = document.getElementById('planChangeModal');
            const closePlanModal = document.getElementById('closePlanModal');
            const planChangeConfirm = document.getElementById('planChangeConfirm');
            const confirmPlanChange = document.getElementById('confirmPlanChange');
            const cancelPlanChange = document.getElementById('cancelPlanChange');
            const exportBillingHistoryBtn = document.getElementById('exportBillingHistoryBtn');

            // 페이지 로드 시 결제 데이터 가져오기
            loadBillingData();
            
            // 결제 수단 드래그 앤 드롭 초기화
            initializePaymentMethodsSorting();

            // 이벤트 리스너 (null 체크 추가)
            if (registerBusinessBtn) registerBusinessBtn.addEventListener('click', showBusinessInfoModal);
            const cancelBusinessInfoBtn = document.getElementById('cancel-business-info');
            if (cancelBusinessInfoBtn) cancelBusinessInfoBtn.addEventListener('click', hideBusinessInfoModal);
            if (businessInfoForm) businessInfoForm.addEventListener('submit', submitBusinessInfo);
            if (downloadReceiptBtn) downloadReceiptBtn.addEventListener('click', downloadReceipt);
            if (addPaymentMethodBtn) addPaymentMethodBtn.addEventListener('click', showPaymentMethodModal);
            if (closePaymentModal) closePaymentModal.addEventListener('click', hidePaymentMethodModal);
            if (cancelPaymentMethod) cancelPaymentMethod.addEventListener('click', hidePaymentMethodModal);
            if (paymentMethodForm) paymentMethodForm.addEventListener('submit', submitPaymentMethod);
            if (changePlanBtn) changePlanBtn.addEventListener('click', showPlanChangeModal);
            if (closePlanModal) closePlanModal.addEventListener('click', hidePlanChangeModal);
            if (cancelPlanChange) cancelPlanChange.addEventListener('click', hidePlanChangeModal);
            if (confirmPlanChange) confirmPlanChange.addEventListener('click', submitPlanChange);
            
            if (exportBillingHistoryBtn) exportBillingHistoryBtn.addEventListener('click', exportBillingHistory);

            // 모달 백드롭 클릭 시 닫기 (null 체크 추가)
            if (businessInfoModal) {
                businessInfoModal.addEventListener('click', function(e) {
                    if (e.target === businessInfoModal) {
                        hideBusinessInfoModal();
                    }
                });
            }

            if (paymentMethodModal) {
                paymentMethodModal.addEventListener('click', function(e) {
                    if (e.target === paymentMethodModal) {
                        hidePaymentMethodModal();
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

            /**
             * 결제 데이터 로드
             */
            async function loadBillingData() {
                if (!currentOrganizationId) return;

                try {
                    const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/data`, {
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
                    currentBillingData = data;

                    updateUI(data);

                } catch (error) {
                    console.error('Failed to load billing data:', error);
                    showError('결제 정보를 불러오는 중 오류가 발생했습니다.');
                }
            }

            /**
             * UI 업데이트
             */
            function updateUI(data) {
                if (data.subscription) {
                    // 구독이 있는 경우
                    if (subscriptionCard) subscriptionCard.classList.remove('hidden');
                    if (noSubscriptionCard) noSubscriptionCard.classList.add('hidden');

                    const planNameEl = document.getElementById('plan-name');
                    const planStatusEl = document.getElementById('plan-status');
                    const nextBillingDateEl = document.getElementById('next-billing-date');
                    const currentMembersEl = document.getElementById('current-members');

                    if (planNameEl) planNameEl.textContent = data.subscription.plan_name + ' 플랜';
                    if (planStatusEl) planStatusEl.textContent = data.subscription.is_active ? '활성' : '비활성';
                    if (nextBillingDateEl) nextBillingDateEl.textContent = data.subscription.next_billing_date;
                    if (currentMembersEl) currentMembersEl.textContent = `${data.usage.members} / ${data.subscription.max_members || '무제한'}`;

                    // 사용량 업데이트
                    updateUsage(data.usage, data.subscription);
                } else {
                    // 구독이 없는 경우
                    if (subscriptionCard) subscriptionCard.classList.add('hidden');
                    if (noSubscriptionCard) noSubscriptionCard.classList.remove('hidden');
                }

                // 사업자 정보 상태에 따른 버튼 표시
                if (data.business_info && data.business_info.has_complete_info) {
                    if (downloadReceiptBtn) downloadReceiptBtn.disabled = false;
                    if (registerBusinessBtn) registerBusinessBtn.classList.add('hidden');
                } else {
                    if (downloadReceiptBtn) downloadReceiptBtn.disabled = true;
                    if (registerBusinessBtn) registerBusinessBtn.classList.remove('hidden');
                }

                // 결제 내역 업데이트
                updateBillingHistory(data.billing_histories);

                // 결제 수단 업데이트
                updatePaymentMethods(data.payment_methods);
            }

            /**
             * 사용량 업데이트
             */
            function updateUsage(usage, subscription) {
                // 기존 하드코딩된 사용량을 실제 데이터로 업데이트
                const usageElements = {
                    members: document.querySelector('[data-usage="members"]'),
                    projects: document.querySelector('[data-usage="projects"]'),
                    storage: document.querySelector('[data-usage="storage"]')
                };

                Object.keys(usageElements).forEach(type => {
                    const element = usageElements[type];
                    if (element) {
                        const current = usage[type];
                        const max = subscription['max_' + type] || '무제한';
                        const percentage = usage.usage_percentages ? usage.usage_percentages[type] : 0;

                        element.querySelector('.usage-text').textContent = `${current} / ${max}`;
                        element.querySelector('.usage-bar').style.width = percentage + '%';
                    }
                });
            }

            /**
             * 결제 내역 업데이트
             */
            function updateBillingHistory(histories) {
                const tbody = document.querySelector('#billing-history-tbody');
                if (!tbody) return;

                tbody.innerHTML = '';

                histories.forEach(history => {
                    const row = createBillingHistoryRow(history);
                    tbody.appendChild(row);
                });
            }

            /**
             * 결제 내역 행 생성
             */
            function createBillingHistoryRow(history) {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';

                const statusColor = getStatusColor(history.status_color);

                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${history.formatted_date}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${history.description}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${history.formatted_amount}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${statusColor}-100 text-${statusColor}-800">
                            ${history.status_text}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        ${history.is_paid ? `<button onclick="downloadSpecificReceipt(${history.id})" class="text-blue-600 hover:text-blue-900">다운로드</button>` : '-'}
                    </td>
                `;

                return row;
            }

            /**
             * 상태 색상 매핑
             */
            function getStatusColor(color) {
                const colorMap = {
                    'green': 'green',
                    'yellow': 'yellow',
                    'red': 'red',
                    'gray': 'gray'
                };
                return colorMap[color] || 'gray';
            }

            /**
             * 사업자 정보 모달 표시
             */
            function showBusinessInfoModal() {
                // 기존 사업자 정보가 있으면 폼에 채워넣기
                if (currentBillingData && currentBillingData.business_info) {
                    const businessInfo = currentBillingData.business_info;
                    const form = businessInfoForm;

                    form.business_name.value = businessInfo.business_name || '';
                    form.business_registration_number.value = businessInfo.business_registration_number || '';
                    form.representative_name.value = businessInfo.representative_name || '';
                    form.business_type.value = businessInfo.business_type || '';
                    form.business_item.value = businessInfo.business_item || '';
                    form.address.value = businessInfo.address || '';
                    form.detail_address.value = businessInfo.detail_address || '';
                    form.phone.value = businessInfo.phone || '';
                    form.email.value = businessInfo.email || '';
                }

                businessInfoModal.classList.remove('hidden');
            }

            /**
             * 사업자 정보 모달 숨김
             */
            function hideBusinessInfoModal() {
                businessInfoModal.classList.add('hidden');
                businessInfoForm.reset();
            }

            /**
             * 사업자 정보 제출
             */
            async function submitBusinessInfo(e) {
                e.preventDefault();

                const formData = new FormData(businessInfoForm);
                const data = {};

                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }

                try {
                    const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/business-info`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data),
                        credentials: 'same-origin'
                    });

                    const result = await response.json();

                    if (result.success) {
                        hideBusinessInfoModal();
                        showSuccess(result.message);
                        loadBillingData(); // 데이터 새로고침
                    } else {
                        showError(result.message);
                    }

                } catch (error) {
                    console.error('Failed to save business info:', error);
                    showError('사업자 정보 저장 중 오류가 발생했습니다.');
                }
            }

            /**
             * 영수증 다운로드
             */
            async function downloadReceipt() {
                if (!currentBillingData || !currentBillingData.billing_histories.length) {
                    showError('다운로드할 영수증이 없습니다.');
                    return;
                }

                const latestPaidHistory = currentBillingData.billing_histories.find(h => h.is_paid);
                if (!latestPaidHistory) {
                    showError('완료된 결제 내역이 없습니다.');
                    return;
                }

                await downloadSpecificReceipt(latestPaidHistory.id);
            }

            /**
             * 특정 영수증 다운로드
             */
            window.downloadSpecificReceipt = async function(billingHistoryId) {
                try {
                    const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/receipt/download`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            billing_history_id: billingHistoryId
                        }),
                        credentials: 'same-origin'
                    });

                    if (response.headers.get('content-type').includes('application/pdf')) {
                        // PDF 다운로드
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = `receipt_${billingHistoryId}.pdf`;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                    } else {
                        // JSON 응답 (URL 리다이렉션)
                        const result = await response.json();
                        if (result.success && result.receipt_url) {
                            window.open(result.receipt_url, '_blank');
                        } else {
                            showError(result.message || '영수증 다운로드에 실패했습니다.');
                        }
                    }

                } catch (error) {
                    console.error('Failed to download receipt:', error);
                    showError('영수증 다운로드 중 오류가 발생했습니다.');
                }
            };

            /**
             * 결제 수단 업데이트
             */
            function updatePaymentMethods(paymentMethods) {
                try {
                    const paymentMethodsContainer = document.querySelector('[data-payment-methods]');
                    if (!paymentMethodsContainer) {
                        console.log('결제 수단 컨테이너를 찾을 수 없습니다.');
                        return;
                    }

                    // 기존 결제 수단 카드들 제거
                    const existingCards = paymentMethodsContainer.querySelectorAll('.payment-method-card');
                    existingCards.forEach(card => card.remove());

                    // 결제 수단 데이터가 있는 경우 새로 렌더링
                    if (paymentMethods && Array.isArray(paymentMethods) && paymentMethods.length > 0) {
                        paymentMethods.forEach((method) => {
                            const methodCard = createPaymentMethodCard(method);
                            paymentMethodsContainer.appendChild(methodCard);
                        });
                    } else {
                        // 결제 수단이 없는 경우 안내 메시지 표시
                        const noPaymentMessage = document.createElement('div');
                        noPaymentMessage.className = 'payment-method-card text-center py-8 text-gray-500';
                        noPaymentMessage.innerHTML = `
                            <p class="text-sm">등록된 결제 수단이 없습니다.</p>
                            <button onclick="showPaymentMethodModal()" class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                결제 수단 추가하기
                            </button>
                        `;
                        paymentMethodsContainer.appendChild(noPaymentMessage);
                    }

                    console.log('결제 수단 정보 업데이트 완료:', paymentMethods);
                } catch (error) {
                    console.error('결제 수단 업데이트 중 오류:', error);
                }
            }

            /**
             * 결제 수단 카드 생성
             */
            function createPaymentMethodCard(method) {
                const card = document.createElement('div');
                card.className = 'payment-method-card bg-white border border-gray-200 rounded-lg p-4';
                card.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    ${method.card_company} ${method.is_default ? '<span class="text-blue-600">(기본)</span>' : ''}
                                </p>
                                <p class="text-sm text-gray-500">**** **** **** ${method.card_number.slice(-4)}</p>
                                <p class="text-xs text-gray-400">만료일: ${method.expiry_date} | ${method.cardholder_name}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            ${!method.is_default ? `
                                <button onclick="setDefaultPaymentMethod(${method.id})" 
                                        class="text-xs text-blue-600 hover:text-blue-800">
                                    기본 설정
                                </button>
                            ` : ''}
                            <button onclick="editPaymentMethod(${method.id})" 
                                    class="text-xs text-gray-600 hover:text-gray-800">
                                수정
                            </button>
                            <button onclick="deletePaymentMethod(${method.id})" 
                                    class="text-xs text-red-600 hover:text-red-800">
                                삭제
                            </button>
                        </div>
                    </div>
                `;
                return card;
            }

            /**
             * 결제 수단 추가 모달 표시
             */
            function showPaymentMethodModal() {
                paymentMethodModal.classList.remove('hidden');
                // 폼 초기화
                paymentMethodForm.reset();
                
                // 폼을 추가 모드로 설정
                paymentMethodForm.dataset.editMode = 'false';
                delete paymentMethodForm.dataset.paymentMethodId;
                
                // 제목 재설정
                const modalTitle = document.querySelector('#paymentMethodModal h3');
                if (modalTitle) modalTitle.textContent = '결제 수단 추가';
                
                // 입력 필드 포맷팅 이벤트 리스너 추가
                setupPaymentFormFormatting();
            }
            
            /**
             * 결제 폼 입력 포맷팅 설정
             */
            function setupPaymentFormFormatting() {
                const cardNumberInput = document.getElementById('cardNumber');
                const expiryDateInput = document.getElementById('expiryDate');
                
                // 카드 번호 포맷팅 (XXXX-XXXX-XXXX-XXXX)
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1-');
                    if (formattedValue.length > 19) {
                        formattedValue = formattedValue.substring(0, 19);
                    }
                    e.target.value = formattedValue;
                });
                
                // 만료일 포맷팅 (MM/YY)
                expiryDateInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });
            }
            
            /**
             * 결제 수단 추가 모달 숨기기
             */
            function hidePaymentMethodModal() {
                paymentMethodModal.classList.add('hidden');
                
                // 폼 초기화 및 추가 모드로 재설정
                paymentMethodForm.reset();
                paymentMethodForm.dataset.editMode = 'false';
                delete paymentMethodForm.dataset.paymentMethodId;
                
                // 제목 재설정
                const modalTitle = document.querySelector('#paymentMethodModal h3');
                if (modalTitle) modalTitle.textContent = '결제 수단 추가';
            }
            
            /**
             * 결제 수단 추가 폼 제출
             */
            async function submitPaymentMethod(e) {
                e.preventDefault();
                
                const cardType = document.getElementById('cardType').value;
                const cardNumber = document.getElementById('cardNumber').value;
                const expiryDate = document.getElementById('expiryDate').value;
                const cvv = document.getElementById('cvv').value;
                const cardholderName = document.getElementById('cardholderName').value;
                const setAsDefault = document.getElementById('setAsDefault').checked;
                
                // 유효성 검사
                if (!cardNumber || !expiryDate || !cvv || !cardholderName) {
                    showError('모든 필드를 입력해주세요.');
                    return;
                }
                
                // 카드 번호 포맷 검사 (16자리 숫자)
                const cleanCardNumber = cardNumber.replace(/\D/g, '');
                if (cleanCardNumber.length !== 16) {
                    showError('올바른 카드 번호를 입력해주세요. (16자리)');
                    return;
                }
                
                // 만료일 포맷 검사 (MM/YY)
                const expiryPattern = /^(0[1-9]|1[0-2])\/\d{2}$/;
                if (!expiryPattern.test(expiryDate)) {
                    showError('올바른 만료일을 입력해주세요. (MM/YY 형식)');
                    return;
                }
                
                // CVV 검사 (3자리 숫자)
                if (!/^\d{3}$/.test(cvv)) {
                    showError('올바른 CVV를 입력해주세요. (3자리 숫자)');
                    return;
                }
                
                try {
                    const saveButton = document.getElementById('savePaymentMethod');
                    saveButton.disabled = true;
                    
                    // 편집 모드 체크
                    const isEditMode = paymentMethodForm.dataset.editMode === 'true';
                    const paymentMethodId = paymentMethodForm.dataset.paymentMethodId;
                    
                    if (isEditMode) {
                        saveButton.textContent = '수정 중...';
                        
                        // 결제 수단 수정 API 호출
                        const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/payment-methods/${paymentMethodId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({
                                card_type: cardType,
                                card_number: cleanCardNumber,
                                expiry_date: expiryDate,
                                cardholder_name: cardholderName
                            }),
                            credentials: 'same-origin'
                        });
                        
                        if (response.ok) {
                            const result = await response.json();
                            showSuccess('결제 수단이 성공적으로 수정되었습니다.');
                        } else {
                            const error = await response.json();
                            throw new Error(error.message || '결제 수단 수정에 실패했습니다.');
                        }
                    } else {
                        saveButton.textContent = '저장 중...';
                        
                        // 결제 수단 추가 API 호출
                        const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/payment-methods`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({
                                card_type: cardType,
                                card_number: cleanCardNumber,
                                expiry_date: expiryDate,
                                cvv: cvv,
                                cardholder_name: cardholderName,
                                set_as_default: setAsDefault
                            }),
                            credentials: 'same-origin'
                        });
                        
                        if (response.ok) {
                            const result = await response.json();
                            showSuccess('결제 수단이 성공적으로 추가되었습니다.');
                        } else {
                            const error = await response.json();
                            throw new Error(error.message || '결제 수단 추가에 실패했습니다.');
                        }
                    }
                    
                    hidePaymentMethodModal();
                    // 결제 데이터 새로고침
                    loadBillingData();
                    
                } catch (error) {
                    console.error('결제 수단 추가 오류:', error);
                    showError(error.message || '결제 수단 추가 중 오류가 발생했습니다.');
                } finally {
                    const saveButton = document.getElementById('savePaymentMethod');
                    saveButton.disabled = false;
                    saveButton.textContent = '저장';
                }
            }

            // 전역 변수로 사용 가능한 플랜 데이터 저장
            let availablePlans = {};

            /**
             * 플랜 변경 모달 표시
             */
            async function showPlanChangeModal() {
                try {
                    // 모달 표시
                    planChangeModal.classList.remove('hidden');
                    planChangeConfirm.classList.add('hidden');
                    
                    // 로딩 상태 표시
                    showPlansLoading();
                    
                    // API에서 사용 가능한 플랜 데이터 가져오기
                    const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/available-plans`, {
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
                    if (data.success && data.data) {
                        // 플랜 데이터를 전역 변수에 저장
                        availablePlans = {};
                        data.data.forEach(plan => {
                            const planKey = plan.slug; // Use slug as key to match HTML data-plan attributes
                            availablePlans[planKey] = {
                                id: plan.id,
                                name: plan.name,
                                price: plan.formatted_price,
                                monthly: plan.monthly_price,
                                description: plan.description,
                                max_members: plan.max_members,
                                max_storage: plan.max_storage,
                                max_projects: plan.max_projects,
                                is_featured: plan.is_featured,
                                features: plan.features
                            };
                        });
                        
                        console.log('Available plans loaded:', availablePlans);
                        
                        // 플랜 목록 렌더링
                        renderPlansList(data.data);
                    } else {
                        throw new Error('Failed to load available plans');
                    }
                } catch (error) {
                    console.error('Failed to load available plans:', error);
                    showPlansError('플랜 정보를 불러오는 중 오류가 발생했습니다.');
                }
            }
            
            /**
             * 플랜 목록 렌더링
             */
            function renderPlansList(plans) {
                const plansContainer = document.getElementById('plans-container');
                if (!plansContainer) return;
                
                // 활성 플랜만 필터링 (is_active가 true인 것만)
                const activePlans = plans.filter(plan => plan.is_active !== false);
                
                plansContainer.innerHTML = activePlans.map(plan => {
                    const isRecommended = plan.is_featured;
                    const planPrice = plan.formatted_price === '문의' ? '문의' : plan.formatted_price;
                    const planType = plan.type === 'usage_based' ? '사용량 기반' : '월간';
                    
                    // 기본 특징들
                    let features = [];
                    if (plan.features && Array.isArray(plan.features)) {
                        features = plan.features;
                    } else {
                        // 플랜 정보에서 기본 특징 생성
                        features = [];
                        if (plan.max_members) {
                            features.push(`최대 ${plan.max_members}명 멤버`);
                        } else {
                            features.push('무제한 멤버');
                        }
                        
                        if (plan.max_storage_gb) {
                            features.push(`${plan.max_storage_gb}GB 스토리지`);
                        } else {
                            features.push('무제한 스토리지');
                        }
                        
                        if (plan.max_projects) {
                            features.push(`${plan.max_projects}개 프로젝트`);
                        } else if (plan.max_projects === null) {
                            features.push('무제한 프로젝트');
                        }
                    }
                    
                    const featuresHtml = features.slice(0, 4).map(feature => `
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            ${feature}
                        </li>
                    `).join('');
                    
                    return `
                        <div class="border-2 ${isRecommended ? 'border-blue-500' : 'border-gray-200'} rounded-lg p-6 hover:border-blue-500 transition-colors cursor-pointer plan-option relative" data-plan="${plan.slug}">
                            ${isRecommended ? `
                                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-medium">추천</span>
                                </div>
                            ` : ''}
                            <div class="text-center">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">${plan.name}</h4>
                                <div class="text-3xl font-bold text-gray-900 mb-1">${planPrice}</div>
                                <div class="text-sm text-gray-500 mb-4">${planType}</div>
                                ${plan.description ? `<p class="text-sm text-gray-600 mb-4">${plan.description}</p>` : ''}
                                <ul class="text-left space-y-2 text-sm text-gray-600">
                                    ${featuresHtml}
                                </ul>
                            </div>
                        </div>
                    `;
                }).join('');
                
                // 이벤트 리스너 다시 추가
                addPlanOptionListeners();
            }
            
            /**
             * 플랜 옵션에 이벤트 리스너 추가
             */
            function addPlanOptionListeners() {
                const planOptions = document.querySelectorAll('.plan-option');
                planOptions.forEach(option => {
                    option.addEventListener('click', () => selectPlan(option.dataset.plan));
                });
            }
            
            /**
             * 플랜 로딩 상태 표시
             */
            function showPlansLoading() {
                const plansContainer = document.getElementById('plans-container');
                if (!plansContainer) return;
                
                plansContainer.innerHTML = `
                    <div id="plans-loading" class="col-span-full text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">요금제 정보를 불러오는 중...</p>
                    </div>
                `;
            }
            
            /**
             * 플랜 로딩 에러 표시
             */
            function showPlansError(message) {
                const plansContainer = document.getElementById('plans-container');
                if (!plansContainer) return;
                
                plansContainer.innerHTML = `
                    <div class="col-span-full text-center py-8">
                        <div class="text-red-500 mb-4">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <p class="text-gray-600 mb-4">${message}</p>
                        <button onclick="showPlanChangeModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            다시 시도
                        </button>
                    </div>
                `;
            }
            
            /**
             * 플랜 변경 모달 숨기기
             */
            function hidePlanChangeModal() {
                planChangeModal.classList.add('hidden');
            }
            
            /**
             * 플랜 선택
             */
            function selectPlan(planType) {
                // API에서 로드된 동적 데이터 사용
                const selectedPlan = availablePlans[planType];
                if (!selectedPlan) {
                    console.error('Selected plan not found:', planType);
                    showError('선택한 플랜을 찾을 수 없습니다.');
                    return;
                }
                
                // 플랜 선택 UI 업데이트
                const planOptions = document.querySelectorAll('.plan-option');
                planOptions.forEach(option => {
                    option.classList.remove('border-blue-500', 'bg-blue-50');
                    option.classList.add('border-gray-200');
                });
                
                const selectedOption = document.querySelector(`[data-plan="${planType}"]`);
                if (selectedOption) {
                    selectedOption.classList.remove('border-gray-200');
                    selectedOption.classList.add('border-blue-500', 'bg-blue-50');
                }
                
                // 확인 섹션 표시
                const selectedPlanNameEl = document.getElementById('selectedPlanName');
                const selectedPlanPriceEl = document.getElementById('selectedPlanPrice');
                const nextBillingDateEl = document.getElementById('nextBillingDate');
                
                if (selectedPlanNameEl) selectedPlanNameEl.textContent = selectedPlan.name + ' 플랜';
                if (selectedPlanPriceEl) selectedPlanPriceEl.textContent = ` - ${selectedPlan.price}/월`;
                
                // 다음 결제일 계산 (현재 날짜 + 1개월)
                const nextMonth = new Date();
                nextMonth.setMonth(nextMonth.getMonth() + 1);
                if (nextBillingDateEl) nextBillingDateEl.textContent = nextMonth.toLocaleDateString('ko-KR');
                
                planChangeConfirm.classList.remove('hidden');
                
                // 확인 버튼에 플랜 정보 저장 (데이터베이스 ID도 포함)
                confirmPlanChange.dataset.planType = planType;
                confirmPlanChange.dataset.planId = selectedPlan.id;
                confirmPlanChange.dataset.planPrice = selectedPlan.monthly;
                
                console.log('Plan selected:', selectedPlan);
            }
            
            /**
             * 플랜 변경 확정
             */
            async function submitPlanChange() {
                const planType = confirmPlanChange.dataset.planType;
                const planId = confirmPlanChange.dataset.planId;
                const planPrice = confirmPlanChange.dataset.planPrice;
                
                if (!planType || !planId) {
                    showError('플랜을 선택해주세요.');
                    return;
                }
                
                try {
                    confirmPlanChange.disabled = true;
                    confirmPlanChange.textContent = '변경 중...';
                    
                    // ProcessPayment API 호출 (플랜 변경은 결제 프로세스의 일부)
                    const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/payment/confirm`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            plan_id: planId,
                            plan_type: planType,
                            monthly_price: planPrice,
                            action: 'plan_change'
                        })
                    });
                    
                    if (response.ok) {
                        const result = await response.json();
                        showSuccess('플랜이 성공적으로 변경되었습니다.');
                        hidePlanChangeModal();
                        // 결제 데이터 새로고침
                        loadBillingData();
                    } else {
                        const error = await response.json();
                        throw new Error(error.message || '플랜 변경에 실패했습니다.');
                    }
                    
                } catch (error) {
                    console.error('플랜 변경 오류:', error);
                    showError(error.message || '플랜 변경 중 오류가 발생했습니다.');
                } finally {
                    confirmPlanChange.disabled = false;
                    confirmPlanChange.textContent = '플랜 변경하기';
                }
            }

            /**
             * 결제 내역 내보내기
             */
            async function exportBillingHistory() {
                const periodSelect = document.querySelector('select');
                const selectedPeriod = periodSelect ? periodSelect.value : '최근 6개월';
                
                try {
                    exportBillingHistoryBtn.disabled = true;
                    exportBillingHistoryBtn.textContent = '내보내는 중...';
                    
                    const response = await fetch(`/api/organizations/${currentOrganizationId}/billing/export`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            period: selectedPeriod,
                            format: 'csv'
                        })
                    });
                    
                    if (response.ok) {
                        const contentType = response.headers.get('content-type');
                        
                        if (contentType && contentType.includes('text/csv')) {
                            // CSV 파일 다운로드
                            const blob = await response.blob();
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = `billing-history-${selectedPeriod.replace(/\s/g, '')}-${new Date().toISOString().split('T')[0]}.csv`;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);
                            showSuccess('결제 내역이 성공적으로 내보내졌습니다.');
                        } else {
                            // JSON 응답
                            const result = await response.json();
                            if (result.success && result.download_url) {
                                window.open(result.download_url, '_blank');
                                showSuccess('결제 내역 내보내기가 완료되었습니다.');
                            } else {
                                throw new Error(result.message || '결제 내역 내보내기에 실패했습니다.');
                            }
                        }
                    } else {
                        const error = await response.json();
                        throw new Error(error.message || '결제 내역 내보내기에 실패했습니다.');
                    }
                    
                } catch (error) {
                    console.error('결제 내역 내보내기 오류:', error);
                    showError(error.message || '결제 내역 내보내기 중 오류가 발생했습니다.');
                } finally {
                    exportBillingHistoryBtn.disabled = false;
                    exportBillingHistoryBtn.textContent = '내보내기';
                }
            }

            /**
             * 결제 수단 편집 (Mock 버전)
             */
            window.editPaymentMethod = function(paymentMethodId) {
                // Mock 데이터 사용
                const mockPaymentMethods = {
                    'card1': { card_company: 'VISA', card_number: '**** **** **** 1234', expiry_date: '12/26', cardholder_name: '홍길동', is_default: true },
                    'card2': { card_company: 'MC', card_number: '**** **** **** 5678', expiry_date: '08/27', cardholder_name: '김철수', is_default: false },
                    'card3': { card_company: 'AMEX', card_number: '**** **** **** 9012', expiry_date: '03/28', cardholder_name: '이영희', is_default: false }
                };

                const paymentMethod = mockPaymentMethods[paymentMethodId];
                if (!paymentMethod) {
                    showError('결제 수단 정보를 찾을 수 없습니다.');
                    return;
                }

                // 간단한 편집 확인 다이얼로그
                const newCardNumber = prompt('새로운 카드 번호 뒷 4자리를 입력하세요:', paymentMethod.card_number.slice(-4));
                if (newCardNumber && newCardNumber !== paymentMethod.card_number.slice(-4)) {
                    paymentMethod.card_number = '**** **** **** ' + newCardNumber;
                    showSuccess('결제 수단이 성공적으로 수정되었습니다.');
                    
                    // UI 업데이트
                    const cardElement = document.querySelector(`[data-method-id="${paymentMethodId}"] p`);
                    if (cardElement) {
                        cardElement.textContent = paymentMethod.card_number;
                    }
                }
            };
            
            /**
             * 결제 수단 삭제 (Mock 버전)
             */
            window.deletePaymentMethod = function(paymentMethodId) {
                if (!confirm('정말로 이 결제 수단을 삭제하시겠습니까?')) {
                    return;
                }
                
                try {
                    // Mock 삭제 처리
                    const cardElement = document.querySelector(`[data-method-id="${paymentMethodId}"]`);
                    if (cardElement) {
                        // 기본 결제 수단인지 확인
                        const isDefault = cardElement.querySelector('.px-2\\.5.py-0\\.5')?.textContent.includes('기본');
                        
                        if (isDefault) {
                            showError('기본 결제 수단은 삭제할 수 없습니다. 다른 카드를 기본으로 설정한 후 삭제해주세요.');
                            return;
                        }
                        
                        // UI에서 카드 제거
                        cardElement.style.transition = 'all 0.3s ease';
                        cardElement.style.opacity = '0';
                        cardElement.style.transform = 'translateX(-100px)';
                        
                        setTimeout(() => {
                            cardElement.remove();
                            updatePaymentMethodsPriority(); // 우선순위 재정렬
                            showSuccess('결제 수단이 성공적으로 삭제되었습니다.');
                        }, 300);
                    } else {
                        showError('결제 수단을 찾을 수 없습니다.');
                    }
                    
                } catch (error) {
                    console.error('결제 수단 삭제 오류:', error);
                    showError('결제 수단 삭제 중 오류가 발생했습니다.');
                }
            };

            /**
             * 기본 결제 수단 설정 (Mock 버전)
             */
            window.setDefaultPaymentMethod = function(paymentMethodId) {
                if (!confirm('이 결제 수단을 기본 결제 수단으로 설정하시겠습니까?')) {
                    return;
                }
                
                try {
                    // 모든 "기본" 라벨 제거
                    document.querySelectorAll('.payment-method-item .bg-blue-100').forEach(badge => {
                        if (badge.textContent.includes('기본')) {
                            badge.remove();
                        }
                    });
                    
                    // 선택한 카드에 "기본" 라벨 추가
                    const selectedCard = document.querySelector(`[data-method-id="${paymentMethodId}"]`);
                    if (selectedCard) {
                        const badgeContainer = selectedCard.querySelector('.flex.gap-2');
                        if (badgeContainer) {
                            const defaultBadge = document.createElement('span');
                            defaultBadge.className = 'px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded-full';
                            defaultBadge.textContent = '기본';
                            badgeContainer.appendChild(defaultBadge);
                        }
                        
                        // 1순위로 설정 (UI 업데이트)
                        const paymentMethodsList = document.getElementById('paymentMethodsList');
                        if (paymentMethodsList) {
                            // 선택한 카드를 맨 위로 이동
                            paymentMethodsList.insertBefore(selectedCard, paymentMethodsList.firstChild);
                            updatePaymentMethodsPriority();
                        }
                        
                        showSuccess('기본 결제 수단이 변경되었습니다.');
                    }
                    
                } catch (error) {
                    console.error('기본 결제 수단 설정 오류:', error);
                    showError('기본 결제 수단 설정 중 오류가 발생했습니다.');
                }
            };

            /**
             * 결제 수단 우선순위 변경
             */
            window.setPriorityPaymentMethod = async function(paymentMethodId) {
                const currentMethod = document.querySelector(`[data-method-id="${paymentMethodId}"]`);
                if (!currentMethod) {
                    showError('결제 수단을 찾을 수 없습니다.');
                    return;
                }

                const currentPriority = parseInt(currentMethod.dataset.priority);
                
                // 우선순위 선택 모달 표시
                const priorityModal = document.createElement('div');
                priorityModal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                priorityModal.innerHTML = `
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3 text-center">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">결제 우선순위 변경</h3>
                            <p class="text-sm text-gray-500 mb-4">새로운 우선순위를 선택하세요</p>
                            <div class="space-y-2 mb-4">
                                ${[1, 2, 3, 4, 5].map(priority => `
                                    <label class="flex items-center justify-center p-2 border rounded cursor-pointer hover:bg-gray-50 ${priority === currentPriority ? 'bg-blue-50 border-blue-300' : 'border-gray-200'}">
                                        <input type="radio" name="priority" value="${priority}" class="mr-2" ${priority === currentPriority ? 'checked' : ''}>
                                        <span class="text-sm">${priority}순위 ${priority === 1 ? '(최우선)' : priority === 2 ? '(2차 결제)' : priority === 3 ? '(3차 결제)' : ''}</span>
                                    </label>
                                `).join('')}
                            </div>
                            <div class="flex justify-center gap-3">
                                <button id="confirmPriority" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">변경</button>
                                <button id="cancelPriority" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">취소</button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(priorityModal);
                
                // 이벤트 리스너
                const confirmBtn = document.getElementById('confirmPriority');
                const cancelBtn = document.getElementById('cancelPriority');
                
                cancelBtn.addEventListener('click', () => {
                    document.body.removeChild(priorityModal);
                });
                
                confirmBtn.addEventListener('click', async () => {
                    const selectedPriority = document.querySelector('input[name="priority"]:checked')?.value;
                    if (!selectedPriority) {
                        showError('우선순위를 선택해주세요.');
                        return;
                    }
                    
                    if (parseInt(selectedPriority) === currentPriority) {
                        document.body.removeChild(priorityModal);
                        return;
                    }
                    
                    try {
                        confirmBtn.disabled = true;
                        confirmBtn.textContent = '변경 중...';
                        
                        // Mock API 호출 대신 UI 직접 업데이트
                        const targetPriority = parseInt(selectedPriority);
                        const paymentMethodsList = document.getElementById('paymentMethodsList');
                        const currentMethodElement = document.querySelector(`[data-method-id="${paymentMethodId}"]`);
                        
                        if (paymentMethodsList && currentMethodElement) {
                            const allMethods = Array.from(paymentMethodsList.children);
                            const targetPosition = targetPriority - 1;
                            
                            if (targetPosition < allMethods.length) {
                                // 해당 위치로 이동
                                if (targetPosition === 0) {
                                    paymentMethodsList.insertBefore(currentMethodElement, paymentMethodsList.firstChild);
                                } else {
                                    const targetElement = allMethods[targetPosition];
                                    paymentMethodsList.insertBefore(currentMethodElement, targetElement);
                                }
                                
                                updatePaymentMethodsPriority(); // UI 업데이트
                                showSuccess('결제 우선순위가 변경되었습니다.');
                                document.body.removeChild(priorityModal);
                            } else {
                                throw new Error('잘못된 우선순위입니다.');
                            }
                        } else {
                            throw new Error('결제 수단을 찾을 수 없습니다.');
                        }
                        
                    } catch (error) {
                        console.error('우선순위 변경 오류:', error);
                        showError(error.message || '우선순위 변경 중 오류가 발생했습니다.');
                    } finally {
                        confirmBtn.disabled = false;
                        confirmBtn.textContent = '변경';
                    }
                });
            };

            /**
             * 결제 수단 우선순위 UI 업데이트
             */
            function updatePaymentMethodsPriority() {
                const paymentMethods = document.querySelectorAll('.payment-method-item');
                const priorities = [
                    { color: 'green', text: '1순위', bgClass: 'bg-green-50 border-green-200' },
                    { color: 'orange', text: '2순위', bgClass: '' },
                    { color: 'gray', text: '3순위', bgClass: '' }
                ];

                paymentMethods.forEach((method, index) => {
                    const priority = index + 1;
                    const priorityInfo = priorities[index] || { color: 'gray', text: `${priority}순위`, bgClass: '' };
                    
                    // 배경색 변경
                    method.className = `payment-method-item flex items-center justify-between p-4 border border-gray-200 rounded-lg ${priorityInfo.bgClass}`;
                    
                    // 우선순위 번호 업데이트
                    const priorityBadge = method.querySelector('.w-8.h-8');
                    if (priorityBadge) {
                        priorityBadge.className = `w-8 h-8 bg-${priorityInfo.color}-600 rounded-full flex items-center justify-center`;
                        priorityBadge.querySelector('span').textContent = priority;
                    }
                    
                    // 우선순위 텍스트 업데이트
                    const priorityText = method.querySelector('.px-2\\.5');
                    if (priorityText) {
                        priorityText.className = `px-2.5 py-0.5 bg-${priorityInfo.color}-100 text-${priorityInfo.color}-800 text-xs font-medium rounded-full`;
                        priorityText.textContent = priorityInfo.text;
                    }
                    
                    // data-priority 업데이트
                    method.dataset.priority = priority;
                });
            }

            /**
             * 결제 수단 드래그 앤 드롭 초기화
             */
            function initializePaymentMethodsSorting() {
                const paymentMethodsList = document.getElementById('paymentMethodsList');
                if (!paymentMethodsList) return;

                let draggedElement = null;
                let placeholder = null;

                // 각 결제 수단 아이템에 드래그 이벤트 추가
                function addDragListeners() {
                    const paymentMethods = document.querySelectorAll('.payment-method-item');
                    
                    paymentMethods.forEach(method => {
                        method.draggable = true;
                        
                        method.addEventListener('dragstart', (e) => {
                            draggedElement = method;
                            method.classList.add('opacity-50');
                            
                            // 플레이스홀더 생성
                            placeholder = document.createElement('div');
                            placeholder.className = 'payment-method-placeholder h-20 border-2 border-dashed border-blue-300 bg-blue-50 rounded-lg flex items-center justify-center';
                            placeholder.innerHTML = '<span class="text-blue-500 text-sm">여기에 놓기</span>';
                            
                            e.dataTransfer.effectAllowed = 'move';
                            e.dataTransfer.setData('text/html', method.outerHTML);
                        });

                        method.addEventListener('dragend', (e) => {
                            method.classList.remove('opacity-50');
                            if (placeholder && placeholder.parentNode) {
                                placeholder.parentNode.removeChild(placeholder);
                            }
                            draggedElement = null;
                            placeholder = null;
                        });

                        method.addEventListener('dragover', (e) => {
                            e.preventDefault();
                            if (draggedElement === method) return;
                            
                            const rect = method.getBoundingClientRect();
                            const midY = rect.top + rect.height / 2;
                            
                            if (e.clientY < midY) {
                                // 위쪽에 삽입
                                method.parentNode.insertBefore(placeholder, method);
                            } else {
                                // 아래쪽에 삽입
                                method.parentNode.insertBefore(placeholder, method.nextSibling);
                            }
                        });

                        method.addEventListener('drop', (e) => {
                            e.preventDefault();
                            if (draggedElement === method) return;
                            
                            // 플레이스홀더 위치에 드래그된 요소 삽입
                            if (placeholder && placeholder.parentNode) {
                                placeholder.parentNode.insertBefore(draggedElement, placeholder);
                                placeholder.parentNode.removeChild(placeholder);
                            }
                            
                            // UI 우선순위 업데이트
                            updatePaymentMethodsPriority();
                            
                            // 서버에 새로운 순서 저장
                            savePaymentMethodsOrder();
                        });
                    });
                }

                // 초기 드래그 리스너 추가
                addDragListeners();

                // MutationObserver로 동적으로 추가되는 결제 수단에도 리스너 추가
                const observer = new MutationObserver(() => {
                    addDragListeners();
                });

                observer.observe(paymentMethodsList, {
                    childList: true,
                    subtree: true
                });
            }

            /**
             * 결제 수단 순서 저장 (Mock 버전)
             */
            function savePaymentMethodsOrder() {
                const paymentMethods = document.querySelectorAll('.payment-method-item');
                const orderData = Array.from(paymentMethods).map((method, index) => ({
                    id: method.dataset.methodId,
                    priority: index + 1
                }));

                // Mock 저장 처리
                console.log('결제 수단 순서 저장 (Mock):', orderData);
                
                // 성공 피드백 (실제 API가 있다면 제거)
                setTimeout(() => {
                    console.log('결제 수단 순서 저장 완료');
                }, 100);
            }

            /**
             * 페이지 변경
             */
            let currentPage = 1;
            let totalPages = 3;
            let itemsPerPage = 10;
            
            window.changePage = function(page) {
                if (page === 'prev') {
                    if (currentPage > 1) {
                        currentPage--;
                        updatePagination();
                        loadBillingHistoryPage(currentPage);
                    }
                } else if (page === 'next') {
                    if (currentPage < totalPages) {
                        currentPage++;
                        updatePagination();
                        loadBillingHistoryPage(currentPage);
                    }
                } else if (typeof page === 'number') {
                    currentPage = page;
                    updatePagination();
                    loadBillingHistoryPage(currentPage);
                }
            };
            
            /**
             * 페이지네이션 UI 업데이트
             */
            function updatePagination() {
                const paginationInfo = document.getElementById('paginationInfo');
                const pageButtons = document.querySelectorAll('.page-btn');
                const prevBtn = document.getElementById('prevPageBtn');
                const nextBtn = document.getElementById('nextPageBtn');
                
                // 정보 업데이트
                const startItem = (currentPage - 1) * itemsPerPage + 1;
                const endItem = Math.min(currentPage * itemsPerPage, currentBillingData?.total_items || 25);
                const totalItems = currentBillingData?.total_items || 25;
                
                if (paginationInfo) {
                    paginationInfo.textContent = `총 ${totalItems}건 중 ${startItem}-${endItem}건 표시`;
                }
                
                // 페이지 버튼 업데이트
                pageButtons.forEach(btn => {
                    const page = parseInt(btn.dataset.page);
                    if (page === currentPage) {
                        btn.className = 'px-3 py-2 bg-blue-600 text-white rounded-lg text-sm page-btn';
                    } else {
                        btn.className = 'px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 page-btn';
                    }
                });
                
                // 이전/다음 버튼 상태 업데이트
                if (prevBtn) {
                    if (currentPage === 1) {
                        prevBtn.className = 'px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed';
                        prevBtn.disabled = true;
                    } else {
                        prevBtn.className = 'px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-500';
                        prevBtn.disabled = false;
                    }
                }
                
                if (nextBtn) {
                    if (currentPage === totalPages) {
                        nextBtn.className = 'px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed';
                        nextBtn.disabled = true;
                    } else {
                        nextBtn.className = 'px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700';
                        nextBtn.disabled = false;
                    }
                }
            }
            
            /**
             * 특정 페이지의 결제 내역 로드
             */
            async function loadBillingHistoryPage(page) {
                try {
                    const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/history?page=${page}&per_page=${itemsPerPage}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        updateBillingHistory(data.billing_histories);
                        
                        // 페이지네이션 정보 업데이트
                        totalPages = Math.ceil(data.total / itemsPerPage);
                        if (currentBillingData) {
                            currentBillingData.total_items = data.total;
                        }
                        
                        updatePagination();
                    } else {
                        console.error('Failed to load billing history page:', response.status);
                    }
                } catch (error) {
                    console.error('Error loading billing history page:', error);
                }
            }

            /**
             * 성공 메시지 표시
             */
            function showSuccess(message) {
                // 간단한 알림 - 실제 프로젝트에서는 toast 라이브러리 사용 권장
                alert(message);
            }

            /**
             * 에러 메시지 표시
             */
            function showError(message) {
                // 간단한 알림 - 실제 프로젝트에서는 toast 라이브러리 사용 권장
                alert(message);
            }
        });

        // ====================
        // 결제 내역 관련 함수들
        // ====================

        /**
         * 결제 내역 상세 보기
         */
        window.showBillingDetail = function(paymentDate) {
            const modal = document.getElementById('billingDetailModal');
            if (!modal) return;

            // Mock 데이터
            const mockBillingDetails = {
                '2024-03-15': {
                    date: '2024.03.15 14:30',
                    status: 'completed',
                    statusText: '결제 완료',
                    statusClass: 'bg-green-100 text-green-800',
                    amount: '₩99,000',
                    paymentId: 'pay_2024031514300001',
                    planName: 'Pro 플랜',
                    period: '2024.03.15 - 2024.04.15',
                    billingCycle: '월간 구독',
                    cardType: 'VISA',
                    cardNumber: '**** **** **** 1234',
                    cardInfo: 'VISA · 만료일: 12/26',
                    cardIcon: 'bg-blue-600',
                    cardIconText: 'VISA',
                    totalAmount: '₩108,000'
                },
                '2024-03-10': {
                    date: '2024.03.10 15:45',
                    status: 'failed',
                    statusText: '결제 실패',
                    statusClass: 'bg-red-100 text-red-800',
                    amount: '₩99,000',
                    paymentId: 'pay_2024031015450002',
                    planName: 'Pro 플랜',
                    period: '2024.03.10 - 2024.04.10',
                    billingCycle: '월간 구독',
                    cardType: 'MasterCard',
                    cardNumber: '**** **** **** 5678',
                    cardInfo: 'MasterCard · 만료일: 08/27',
                    cardIcon: 'bg-red-600',
                    cardIconText: 'MC',
                    totalAmount: '₩108,000',
                    failureReason: '한도 초과',
                    failureTime: '2024.03.10 15:45',
                    errorCode: 'LIMIT_EXCEEDED'
                },
                '2024-02-15': {
                    date: '2024.02.15 09:20',
                    status: 'completed',
                    statusText: '결제 완료',
                    statusClass: 'bg-green-100 text-green-800',
                    amount: '₩99,000',
                    paymentId: 'pay_2024021509200003',
                    planName: 'Pro 플랜',
                    period: '2024.02.15 - 2024.03.15',
                    billingCycle: '월간 구독',
                    cardType: 'VISA',
                    cardNumber: '**** **** **** 1234',
                    cardInfo: 'VISA · 만료일: 12/26',
                    cardIcon: 'bg-blue-600',
                    cardIconText: 'VISA',
                    totalAmount: '₩108,000'
                },
                '2024-01-20': {
                    date: '2024.01.20 16:10',
                    status: 'refunded',
                    statusText: '환불 완료',
                    statusClass: 'bg-purple-100 text-purple-800',
                    amount: '-₩33,000',
                    paymentId: 'ref_2024012016100004',
                    planName: 'Pro 플랜 환불',
                    period: '부분 환불',
                    billingCycle: '중도 해지',
                    cardType: 'VISA',
                    cardNumber: '**** **** **** 1234',
                    cardInfo: 'VISA · 만료일: 12/26',
                    cardIcon: 'bg-blue-600',
                    cardIconText: 'VISA',
                    totalAmount: '-₩33,000',
                    refundReason: '중도 해지',
                    refundAmount: '₩33,000',
                    refundDate: '2024.01.25'
                },
                '2024-01-15': {
                    date: '2024.01.15 13:00',
                    status: 'pending',
                    statusText: '결제 대기',
                    statusClass: 'bg-yellow-100 text-yellow-800',
                    amount: '₩99,000',
                    paymentId: 'pend_2024011513000005',
                    planName: 'Pro 플랜',
                    period: '2024.01.15 - 2024.02.15',
                    billingCycle: '월간 구독',
                    cardType: '결제 대기',
                    cardNumber: '결제 수단 선택 필요',
                    cardInfo: '결제 대기 중',
                    cardIcon: 'bg-gray-400',
                    cardIconText: '-',
                    totalAmount: '₩108,000'
                }
            };

            const detail = mockBillingDetails[paymentDate];
            if (!detail) {
                alert('결제 내역을 찾을 수 없습니다.');
                return;
            }

            // 기본 정보 업데이트
            document.getElementById('detailDate').textContent = detail.date;
            document.getElementById('detailStatus').textContent = detail.statusText;
            document.getElementById('detailStatus').className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${detail.statusClass}`;
            document.getElementById('detailAmount').textContent = detail.amount;
            document.getElementById('detailPaymentId').textContent = detail.paymentId;

            // 플랜 정보 업데이트
            document.getElementById('detailPlanName').textContent = detail.planName;
            document.getElementById('detailPeriod').textContent = detail.period;
            document.getElementById('detailBillingCycle').textContent = detail.billingCycle;

            // 결제 수단 정보 업데이트
            document.getElementById('detailCardIcon').className = `w-12 h-8 ${detail.cardIcon} rounded flex items-center justify-center`;
            document.getElementById('detailCardIcon').innerHTML = `<span class="text-white text-xs font-bold">${detail.cardIconText}</span>`;
            document.getElementById('detailCardNumber').textContent = detail.cardNumber;
            document.getElementById('detailCardInfo').textContent = detail.cardInfo;

            // 총 금액 업데이트
            document.getElementById('detailTotalAmount').textContent = detail.totalAmount;

            // 실패 정보 처리
            const failureInfo = document.getElementById('failureInfo');
            if (detail.status === 'failed' && detail.failureReason) {
                document.getElementById('failureReason').textContent = detail.failureReason;
                document.getElementById('failureTime').textContent = detail.failureTime;
                document.getElementById('errorCode').textContent = detail.errorCode;
                failureInfo.classList.remove('hidden');
            } else {
                failureInfo.classList.add('hidden');
            }

            // 환불 정보 처리
            const refundInfo = document.getElementById('refundInfo');
            if (detail.status === 'refunded' && detail.refundReason) {
                document.getElementById('refundReason').textContent = detail.refundReason;
                document.getElementById('refundAmount').textContent = detail.refundAmount;
                document.getElementById('refundDate').textContent = detail.refundDate;
                refundInfo.classList.remove('hidden');
            } else {
                refundInfo.classList.add('hidden');
            }

            // 액션 버튼 처리
            const retryBtn = document.getElementById('retryPaymentBtn');
            if (detail.status === 'failed' || detail.status === 'pending') {
                retryBtn.classList.remove('hidden');
            } else {
                retryBtn.classList.add('hidden');
            }

            modal.classList.remove('hidden');
        };

        /**
         * 결제 내역 상세 모달 닫기
         */
        document.addEventListener('DOMContentLoaded', function() {
            const closeBtn = document.getElementById('closeBillingDetailModal');
            const modal = document.getElementById('billingDetailModal');
            
            if (closeBtn && modal) {
                closeBtn.addEventListener('click', function() {
                    modal.classList.add('hidden');
                });

                // 모달 배경 클릭시 닫기
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });

        /**
         * 영수증 다운로드 (특정 결제 내역)
         */
        window.downloadSpecificReceipt = function(paymentDate) {
            console.log('영수증 다운로드:', paymentDate);
            // Mock 다운로드
            const link = document.createElement('a');
            link.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(`영수증\n결제일: ${paymentDate}\n금액: ₩99,000\n상태: 결제 완료`);
            link.download = `receipt_${paymentDate.replace(/\./g, '')}.txt`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        /**
         * 결제 재시도
         */
        window.retryPayment = function(paymentDate) {
            console.log('결제 재시도:', paymentDate);
            
            if (confirm('결제를 재시도하시겠습니까?')) {
                // Mock 재시도 처리
                const loadingBtn = event.target;
                const originalText = loadingBtn.textContent;
                
                loadingBtn.textContent = '처리 중...';
                loadingBtn.disabled = true;
                
                setTimeout(() => {
                    alert('결제가 성공적으로 처리되었습니다.');
                    
                    // 상태 업데이트 (실제로는 페이지 새로고침이나 데이터 갱신이 필요)
                    loadingBtn.textContent = originalText;
                    loadingBtn.disabled = false;
                    
                    // 모달 닫기
                    document.getElementById('billingDetailModal').classList.add('hidden');
                }, 2000);
            }
        };

        /**
         * 결제 처리 (결제 대기 상태)
         */
        window.processPayment = function(paymentDate) {
            console.log('결제 처리:', paymentDate);
            
            if (confirm('결제를 진행하시겠습니까?')) {
                // Mock 결제 처리
                const loadingBtn = event.target;
                const originalText = loadingBtn.textContent;
                
                loadingBtn.textContent = '처리 중...';
                loadingBtn.disabled = true;
                
                setTimeout(() => {
                    alert('결제가 성공적으로 완료되었습니다.');
                    
                    loadingBtn.textContent = originalText;
                    loadingBtn.disabled = false;
                    
                    // 실제로는 테이블 행 상태 업데이트가 필요
                    location.reload(); // 임시로 페이지 새로고림
                }, 2000);
            }
        };

        /**
         * 결제 내역 검색
         */
        function initializeBillingHistorySearch() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const periodFilter = document.getElementById('periodFilter');
            
            if (!searchInput || !statusFilter || !periodFilter) return;

            function filterBillingHistory() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const periodValue = periodFilter.value;
                
                const rows = document.querySelectorAll('#billing-history-tbody tr');
                let visibleCount = 0;
                
                rows.forEach(row => {
                    let shouldShow = true;
                    
                    // 검색어 필터
                    if (searchTerm) {
                        const text = row.textContent.toLowerCase();
                        shouldShow = shouldShow && text.includes(searchTerm);
                    }
                    
                    // 상태 필터
                    if (statusValue !== 'all') {
                        const statusElement = row.querySelector('span[class*="bg-"]');
                        const statusText = statusElement ? statusElement.textContent.toLowerCase() : '';
                        
                        const statusMap = {
                            'completed': '결제 완료',
                            'failed': '결제 실패',
                            'refunded': '환불 완료',
                            'pending': '결제 대기'
                        };
                        
                        shouldShow = shouldShow && statusText.includes(statusMap[statusValue]);
                    }
                    
                    if (shouldShow) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // 총 건수 업데이트
                const totalRecords = document.getElementById('totalRecords');
                if (totalRecords) {
                    totalRecords.textContent = visibleCount;
                }
            }

            searchInput.addEventListener('input', filterBillingHistory);
            statusFilter.addEventListener('change', filterBillingHistory);
            periodFilter.addEventListener('change', filterBillingHistory);
        }

        /**
         * 페이지네이션
         */
        window.changePage = function(page) {
            console.log('페이지 변경:', page);
            
            // Mock 페이지네이션 처리
            const pageButtons = document.querySelectorAll('.page-btn');
            pageButtons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('border', 'border-gray-300', 'text-gray-700');
            });
            
            if (typeof page === 'number') {
                const targetBtn = document.querySelector(`[data-page="${page}"]`);
                if (targetBtn) {
                    targetBtn.classList.remove('border', 'border-gray-300', 'text-gray-700');
                    targetBtn.classList.add('bg-blue-600', 'text-white');
                }
            }
            
            // 실제로는 서버에서 해당 페이지 데이터를 가져와야 함
            console.log('페이지 데이터 로딩 시뮬레이션');
        };

        /**
         * 결제 내역 내보내기
         */
        document.addEventListener('DOMContentLoaded', function() {
            const exportBtn = document.getElementById('exportBillingHistoryBtn');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    console.log('결제 내역 내보내기');
                    
                    // Mock CSV 생성
                    const csvContent = '날짜,설명,결제수단,금액,상태\n2024.03.15,Pro 플랜 월간 구독,**** 1234,₩99000,결제완료\n2024.03.10,Pro 플랜 월간 구독,**** 5678,₩99000,결제실패\n2024.02.15,Pro 플랜 월간 구독,**** 1234,₩99000,결제완료';
                    
                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', 'billing_history.csv');
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            }

            // 검색 기능 초기화
            initializeBillingHistorySearch();
            
            // 결제 내역 필터링 기능 초기화
            initializeBillingFiltering();

            // 영수증 다운로드 및 고객지원 버튼 이벤트
            const downloadReceiptBtn = document.getElementById('downloadReceiptBtn');
            const retryPaymentBtn = document.getElementById('retryPaymentBtn');
            const contactSupportBtn = document.getElementById('contactSupportBtn');

            if (downloadReceiptBtn) {
                downloadReceiptBtn.addEventListener('click', function() {
                    const paymentId = document.getElementById('detailPaymentId').textContent;
                    downloadSpecificReceipt(paymentId);
                });
            }

            if (retryPaymentBtn) {
                retryPaymentBtn.addEventListener('click', function() {
                    const paymentId = document.getElementById('detailPaymentId').textContent;
                    retryPayment(paymentId);
                });
            }

            if (contactSupportBtn) {
                contactSupportBtn.addEventListener('click', function() {
                    alert('고객지원 팀에 문의하시겠습니까?\n\n📞 전화: 1588-0000\n📧 이메일: support@company.com\n💬 채팅: 우측 하단 채팅 버튼');
                });
            }
        });

        /**
         * 결제 내역 필터링 및 AJAX 기능 초기화
         */
        function initializeBillingFiltering() {
            const periodFilter = document.getElementById('periodFilter');
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('searchInput');
            const exportBtn = document.getElementById('exportBillingHistoryBtn');
            
            let debounceTimer;
            
            // 필터링 함수
            async function applyFilters() {
                const period = periodFilter ? periodFilter.value : '6months';
                const status = statusFilter ? statusFilter.value : 'all';
                const search = searchInput ? searchInput.value : '';
                
                const params = new URLSearchParams({
                    period: period,
                    status: status,
                    search: search
                });
                
                try {
                    const currentUrl = window.location.href;
                    const baseUrl = currentUrl.split('?')[0];
                    
                    // AJAX 요청으로 필터된 데이터 가져오기
                    const response = await fetch(`${baseUrl}?${params.toString()}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            updateBillingHistoryTable(data.data.billingHistories);
                            updatePaginationInfo(data.data.pagination);
                            updateTotalRecords(data.data.pagination.total);
                        }
                    } else {
                        console.error('Failed to filter billing history:', response.status);
                    }
                } catch (error) {
                    console.error('Error filtering billing history:', error);
                }
            }
            
            // 이벤트 리스너
            if (periodFilter) {
                periodFilter.addEventListener('change', applyFilters);
            }
            
            if (statusFilter) {
                statusFilter.addEventListener('change', applyFilters);
            }
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(applyFilters, 500); // 500ms 디바운스
                });
            }
            
            if (exportBtn) {
                exportBtn.addEventListener('click', async function() {
                    const period = periodFilter ? periodFilter.value : '6months';
                    const status = statusFilter ? statusFilter.value : 'all';
                    const search = searchInput ? searchInput.value : '';
                    
                    try {
                        exportBtn.disabled = true;
                        exportBtn.textContent = '내보내는 중...';
                        
                        const params = new URLSearchParams({
                            period: period,
                            status: status,
                            search: search
                        });
                        
                        const currentUrl = window.location.href;
                        const baseUrl = currentUrl.split('?')[0];
                        const exportUrl = baseUrl.replace('/billing/payment-history', '/billing/export');
                        
                        const response = await fetch(`${exportUrl}?${params.toString()}`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            }
                        });
                        
                        if (response.ok) {
                            const blob = await response.blob();
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = `billing_history_${new Date().toISOString().split('T')[0]}.csv`;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);
                        } else {
                            alert('내보내기에 실패했습니다.');
                        }
                    } catch (error) {
                        console.error('Export error:', error);
                        alert('내보내기 중 오류가 발생했습니다.');
                    } finally {
                        exportBtn.disabled = false;
                        exportBtn.textContent = '내보내기';
                    }
                });
            }
        }
        
        /**
         * 결제 내역 테이블 업데이트
         */
        function updateBillingHistoryTable(billingHistories) {
            const tbody = document.getElementById('billing-history-tbody');
            if (!tbody) return;
            
            tbody.innerHTML = '';
            
            if (billingHistories.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm">조건에 맞는 결제 내역이 없습니다.</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            billingHistories.forEach(history => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 cursor-pointer';
                row.onclick = () => showBillingDetail(history.id);
                
                const cardIcon = getCardIcon(history.card_company);
                const statusBadge = getStatusBadge(history.status);
                const actionButtons = getActionButtons(history);
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${history.formatted_date || history.approved_at || history.requested_at}</td>
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">${history.description}</div>
                            <div class="text-sm text-gray-500">
                                ${history.subscription ? history.subscription.plan_name : ''}
                                ${history.method ? '· ' + history.method : ''}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            ${cardIcon}
                            <span class="text-sm text-gray-600">${history.card_number ? '**** ' + history.card_number.slice(-4) : (history.method || '결제 대기')}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${history.formatted_amount || '₩' + history.amount?.toLocaleString()}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex gap-2 justify-end">
                            ${actionButtons}
                            <button onclick="event.stopPropagation(); showBillingDetail('${history.id}')" class="text-gray-600 hover:text-gray-900">상세보기</button>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }
        
        /**
         * 카드 아이콘 생성
         */
        function getCardIcon(cardCompany) {
            const iconMap = {
                'VISA': { bg: 'bg-blue-600', text: 'V' },
                'Mastercard': { bg: 'bg-red-600', text: 'M' },
                'MasterCard': { bg: 'bg-red-600', text: 'M' },
                '삼성카드': { bg: 'bg-gray-600', text: 'S' }
            };
            
            const icon = iconMap[cardCompany] || { bg: 'bg-gray-400', text: '-' };
            
            return `
                <div class="w-6 h-4 rounded flex items-center justify-center ${icon.bg}">
                    <span class="text-white text-xs font-bold">${icon.text}</span>
                </div>
            `;
        }
        
        /**
         * 상태 배지 생성
         */
        function getStatusBadge(status) {
            const statusMap = {
                'DONE': { color: 'green', text: '결제 완료' },
                'READY': { color: 'yellow', text: '결제 대기' },
                'IN_PROGRESS': { color: 'yellow', text: '결제 진행 중' },
                'WAITING_FOR_DEPOSIT': { color: 'yellow', text: '입금 대기' },
                'CANCELED': { color: 'red', text: '결제 취소' },
                'PARTIAL_CANCELED': { color: 'purple', text: '환불 완료' },
                'ABORTED': { color: 'red', text: '결제 중단' },
                'EXPIRED': { color: 'red', text: '결제 만료' }
            };
            
            const statusInfo = statusMap[status] || { color: 'gray', text: status };
            
            return `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${statusInfo.color}-100 text-${statusInfo.color}-800">
                    ${statusInfo.text}
                </span>
            `;
        }
        
        /**
         * 액션 버튼 생성
         */
        function getActionButtons(history) {
            const buttons = [];
            
            if (history.status === 'DONE') {
                buttons.push(`<button onclick="event.stopPropagation(); downloadSpecificReceipt('${history.id}')" class="text-blue-600 hover:text-blue-900">영수증</button>`);
            } else if (['CANCELED', 'PARTIAL_CANCELED', 'ABORTED', 'EXPIRED'].includes(history.status)) {
                buttons.push(`<button onclick="event.stopPropagation(); retryPayment('${history.id}')" class="text-orange-600 hover:text-orange-900">재시도</button>`);
            } else if (history.status === 'READY') {
                buttons.push(`<button onclick="event.stopPropagation(); processPayment('${history.id}')" class="text-green-600 hover:text-green-900">결제하기</button>`);
            }
            
            return buttons.join('');
        }
        
        /**
         * 페이지네이션 정보 업데이트
         */
        function updatePaginationInfo(pagination) {
            const paginationInfo = document.getElementById('paginationInfo');
            if (paginationInfo) {
                paginationInfo.textContent = `총 ${pagination.total}건 중 ${pagination.from || 0}-${pagination.to || 0}건 표시`;
            }
            
            // 페이지네이션 버튼 업데이트
            const paginationContainer = document.getElementById('paginationContainer');
            if (paginationContainer && pagination.last_page > 1) {
                updatePaginationButtons(pagination);
            }
        }
        
        /**
         * 페이지네이션 버튼 업데이트
         */
        function updatePaginationButtons(pagination) {
            const buttonsContainer = document.getElementById('paginationButtons');
            if (!buttonsContainer) return;
            
            let buttons = [];
            
            // 이전 버튼
            if (pagination.current_page === 1) {
                buttons.push(`<button disabled class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed">이전</button>`);
            } else {
                buttons.push(`<button onclick="changePage(${pagination.current_page - 1})" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-500 hover:bg-gray-50">이전</button>`);
            }
            
            // 페이지 번호 버튼
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
            
            for (let page = startPage; page <= endPage; page++) {
                if (page === pagination.current_page) {
                    buttons.push(`<button class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm page-btn" data-page="${page}">${page}</button>`);
                } else {
                    buttons.push(`<button onclick="changePage(${page})" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 page-btn" data-page="${page}">${page}</button>`);
                }
            }
            
            // 다음 버튼
            if (pagination.current_page === pagination.last_page) {
                buttons.push(`<button disabled class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed">다음</button>`);
            } else {
                buttons.push(`<button onclick="changePage(${pagination.current_page + 1})" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">다음</button>`);
            }
            
            buttonsContainer.innerHTML = buttons.join('');
        }
        
        /**
         * 총 건수 업데이트
         */
        function updateTotalRecords(total) {
            const totalRecordsEl = document.getElementById('totalRecords');
            if (totalRecordsEl) {
                totalRecordsEl.textContent = total;
            }
        }
        
        /**
         * 페이지 변경 (실제 AJAX 버전)
         */
        window.changePage = async function(page) {
            const periodFilter = document.getElementById('periodFilter');
            const statusFilter = document.getElementById('statusFilter'); 
            const searchInput = document.getElementById('searchInput');
            
            const params = new URLSearchParams({
                page: page,
                period: periodFilter ? periodFilter.value : '6months',
                status: statusFilter ? statusFilter.value : 'all',
                search: searchInput ? searchInput.value : ''
            });
            
            try {
                const currentUrl = window.location.href;
                const baseUrl = currentUrl.split('?')[0];
                
                const response = await fetch(`${baseUrl}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        updateBillingHistoryTable(data.data.billingHistories);
                        updatePaginationInfo(data.data.pagination);
                        updateTotalRecords(data.data.pagination.total);
                    }
                } else {
                    console.error('Failed to change page:', response.status);
                }
            } catch (error) {
                console.error('Error changing page:', error);
            }
        };
    </script>
</body>
</html>
