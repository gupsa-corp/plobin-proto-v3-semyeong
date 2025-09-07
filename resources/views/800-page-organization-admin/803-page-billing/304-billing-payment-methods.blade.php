{{-- 결제 수단 --}}
<style>
.payment-method-item {
    transition: all 0.2s ease;
    cursor: grab;
}
.payment-method-item:active {
    cursor: grabbing;
}
.payment-method-item.dragging {
    transform: rotate(3deg);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    z-index: 1000;
}
.payment-method-placeholder {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
.priority-btn:hover {
    transform: scale(1.1);
}
</style>

<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">결제 수단</h3>
        <button id="addPaymentMethodBtn" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
            + 결제 수단 추가
        </button>
    </div>

    <div class="text-sm text-gray-600 mb-3">
        결제 시도 시 1순위부터 차례로 결제를 시도합니다. 실패하면 다음 우선순위 카드로 결제됩니다.
    </div>

    <div id="paymentMethodsList" class="space-y-3">
        <!-- 1순위 결제 수단 -->
        <div class="payment-method-item flex items-center justify-between p-4 border border-gray-200 rounded-lg bg-green-50 border-green-200" data-method-id="card1" data-priority="1">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-bold">1</span>
                    </div>
                    <button class="priority-btn text-gray-400 hover:text-gray-600 cursor-move" title="드래그하여 순서 변경">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 2a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H8a1 1 0 01-1-1V2zM7 8a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H8a1 1 0 01-1-1V8zM8 13a1 1 0 00-1 1v2a1 1 0 001 1h4a1 1 0 001-1v-2a1 1 0 00-1-1H8z"/>
                        </svg>
                    </button>
                </div>
                <div class="w-12 h-8 bg-blue-600 rounded flex items-center justify-center">
                    <span class="text-white text-xs font-bold">VISA</span>
                </div>
                <div>
                    <p class="font-medium text-gray-900">**** **** **** 1234</p>
                    <p class="text-sm text-gray-500">만료일: 12/26</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-2.5 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded-full">1순위</span>
                    <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">기본</span>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="editPaymentMethod('card1')" class="text-blue-600 hover:text-blue-800 text-sm">편집</button>
                <button onclick="setPriorityPaymentMethod('card1')" class="text-purple-600 hover:text-purple-800 text-sm">우선순위 변경</button>
                <button onclick="deletePaymentMethod('card1')" class="text-red-600 hover:text-red-800 text-sm">삭제</button>
            </div>
        </div>

        <!-- 2순위 결제 수단 -->
        <div class="payment-method-item flex items-center justify-between p-4 border border-gray-200 rounded-lg" data-method-id="card2" data-priority="2">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-bold">2</span>
                    </div>
                    <button class="priority-btn text-gray-400 hover:text-gray-600 cursor-move" title="드래그하여 순서 변경">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 2a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H8a1 1 0 01-1-1V2zM7 8a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H8a1 1 0 01-1-1V8zM8 13a1 1 0 00-1 1v2a1 1 0 001 1h4a1 1 0 001-1v-2a1 1 0 00-1-1H8z"/>
                        </svg>
                    </button>
                </div>
                <div class="w-12 h-8 bg-red-600 rounded flex items-center justify-center">
                    <span class="text-white text-xs font-bold">MC</span>
                </div>
                <div>
                    <p class="font-medium text-gray-900">**** **** **** 5678</p>
                    <p class="text-sm text-gray-500">만료일: 08/27</p>
                </div>
                <span class="px-2.5 py-0.5 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">2순위</span>
            </div>
            <div class="flex gap-2">
                <button onclick="editPaymentMethod('card2')" class="text-blue-600 hover:text-blue-800 text-sm">편집</button>
                <button onclick="setPriorityPaymentMethod('card2')" class="text-purple-600 hover:text-purple-800 text-sm">우선순위 변경</button>
                <button onclick="setDefaultPaymentMethod('card2')" class="text-green-600 hover:text-green-800 text-sm">기본으로 설정</button>
                <button onclick="deletePaymentMethod('card2')" class="text-red-600 hover:text-red-800 text-sm">삭제</button>
            </div>
        </div>

    </div>

    <!-- 결제 우선순위 안내 -->
    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h4 class="text-sm font-medium text-blue-800">결제 처리 방식</h4>
                <p class="text-sm text-blue-700 mt-1">
                    • 1순위 카드부터 차례로 결제 시도<br>
                    • 결제 실패 시 자동으로 다음 순위 카드로 결제<br>
                    • 모든 카드 결제 실패 시 결제 실패 처리
                </p>
            </div>
        </div>
    </div>
</div>