<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Organization;
use App\Models\BillingHistory;
use App\Models\PaymentMethod;

class TossPaymentsService
{
    private string $baseUrl;
    private string $secretKey;
    private array $headers;

    public function __construct()
    {
        $this->baseUrl = config('services.toss.api_url', 'https://api.tosspayments.com');
        $this->secretKey = config('services.toss.secret_key');
        $this->headers = [
            'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * 결제 승인
     */
    public function confirmPayment(string $paymentKey, string $orderId, int $amount): array
    {
        $url = "{$this->baseUrl}/v1/payments/confirm";
        
        $response = Http::withHeaders($this->headers)->post($url, [
            'paymentKey' => $paymentKey,
            'orderId' => $orderId,
            'amount' => $amount,
        ]);

        $this->logResponse('confirmPayment', $response);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Payment confirmation failed: ' . $response->body());
    }

    /**
     * 결제 조회
     */
    public function getPayment(string $paymentKey): array
    {
        $url = "{$this->baseUrl}/v1/payments/{$paymentKey}";
        
        $response = Http::withHeaders($this->headers)->get($url);
        
        $this->logResponse('getPayment', $response);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Payment retrieval failed: ' . $response->body());
    }

    /**
     * 결제 취소
     */
    public function cancelPayment(string $paymentKey, string $cancelReason, ?int $cancelAmount = null): array
    {
        $url = "{$this->baseUrl}/v1/payments/{$paymentKey}/cancel";
        
        $data = [
            'cancelReason' => $cancelReason,
        ];

        if ($cancelAmount !== null) {
            $data['cancelAmount'] = $cancelAmount;
        }

        $response = Http::withHeaders($this->headers)->post($url, $data);
        
        $this->logResponse('cancelPayment', $response);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Payment cancellation failed: ' . $response->body());
    }

    /**
     * 빌링키 발급 (자동결제용)
     */
    public function issueBillingKey(string $customerKey, string $authKey): array
    {
        $url = "{$this->baseUrl}/v1/billing/authorizations/issue";
        
        $response = Http::withHeaders($this->headers)->post($url, [
            'customerKey' => $customerKey,
            'authKey' => $authKey,
        ]);

        $this->logResponse('issueBillingKey', $response);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Billing key issuance failed: ' . $response->body());
    }

    /**
     * 빌링키로 결제
     */
    public function payWithBillingKey(
        string $billingKey, 
        string $customerKey, 
        int $amount, 
        string $orderId, 
        string $orderName
    ): array {
        $url = "{$this->baseUrl}/v1/billing/{$billingKey}";
        
        $response = Http::withHeaders($this->headers)->post($url, [
            'customerKey' => $customerKey,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderName' => $orderName,
        ]);

        $this->logResponse('payWithBillingKey', $response);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Billing payment failed: ' . $response->body());
    }

    /**
     * 정기결제 처리 (조직의 월간 구독)
     */
    public function processMonthlyBilling(Organization $organization): ?BillingHistory
    {
        $subscription = $organization->activeSubscription;
        if (!$subscription) {
            Log::warning("No active subscription for organization {$organization->id}");
            return null;
        }

        $paymentMethod = $organization->defaultPaymentMethod;
        if (!$paymentMethod) {
            Log::warning("No default payment method for organization {$organization->id}");
            return null;
        }

        $orderId = 'subscription_' . $subscription->id . '_' . now()->format('YmdHis');
        $orderName = $subscription->plan_name . ' 플랜 월간 구독';

        try {
            $result = $this->payWithBillingKey(
                $paymentMethod->billing_key,
                "org_{$organization->id}",
                $subscription->monthly_price,
                $orderId,
                $orderName
            );

            // 결제 내역 저장
            $billingHistory = BillingHistory::create([
                'organization_id' => $organization->id,
                'subscription_id' => $subscription->id,
                'payment_key' => $result['paymentKey'],
                'order_id' => $orderId,
                'description' => $orderName,
                'amount' => $subscription->monthly_price,
                'vat' => round($subscription->monthly_price / 11),
                'status' => $result['status'],
                'method' => $result['method']['type'] ?? 'card',
                'requested_at' => now(),
                'approved_at' => $result['approvedAt'] ? new \DateTime($result['approvedAt']) : null,
                'toss_response' => $result,
                'receipt_url' => $result['receipt']['url'] ?? null,
                'card_number' => $result['card']['number'] ?? null,
                'card_company' => $result['card']['company'] ?? null,
            ]);

            // 구독 정보 업데이트 (다음 결제일)
            $subscription->update([
                'next_billing_date' => $subscription->next_billing_date->addMonth(),
                'current_period_end' => $subscription->current_period_end->addMonth(),
            ]);

            return $billingHistory;

        } catch (\Exception $e) {
            Log::error("Monthly billing failed for organization {$organization->id}: " . $e->getMessage());
            
            // 실패한 결제 내역도 저장
            BillingHistory::create([
                'organization_id' => $organization->id,
                'subscription_id' => $subscription->id,
                'payment_key' => '',
                'order_id' => $orderId,
                'description' => $orderName,
                'amount' => $subscription->monthly_price,
                'status' => 'ABORTED',
                'method' => 'card',
                'requested_at' => now(),
                'toss_response' => ['error' => $e->getMessage()],
            ]);

            return null;
        }
    }

    /**
     * 웹훅 검증
     */
    public function verifyWebhook(string $signature, string $body): bool
    {
        $expectedSignature = base64_encode(hash_hmac('sha256', $body, $this->secretKey, true));
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * 결제 내역 업데이트 (웹훅용)
     */
    public function updateBillingHistoryFromWebhook(array $webhookData): void
    {
        $paymentKey = $webhookData['data']['paymentKey'] ?? null;
        if (!$paymentKey) {
            return;
        }

        $billingHistory = BillingHistory::where('payment_key', $paymentKey)->first();
        if (!$billingHistory) {
            Log::warning("Billing history not found for payment key: {$paymentKey}");
            return;
        }

        $billingHistory->update([
            'status' => $webhookData['data']['status'],
            'approved_at' => isset($webhookData['data']['approvedAt']) 
                ? new \DateTime($webhookData['data']['approvedAt']) 
                : null,
            'toss_response' => array_merge($billingHistory->toss_response ?? [], $webhookData['data']),
        ]);

        Log::info("Updated billing history {$billingHistory->id} from webhook");
    }

    /**
     * 응답 로깅
     */
    private function logResponse(string $method, Response $response): void
    {
        Log::info("Toss Payments API {$method}", [
            'status' => $response->status(),
            'response' => $response->successful() ? 'SUCCESS' : 'FAILED',
            'body' => $response->body(),
        ]);
    }

    /**
     * 고유한 주문 ID 생성
     */
    public function generateOrderId(string $prefix = 'order'): string
    {
        return $prefix . '_' . time() . '_' . mt_rand(1000, 9999);
    }

    /**
     * 영수증 URL 생성
     */
    public function generateReceiptUrl(string $paymentKey): string
    {
        return "{$this->baseUrl}/v1/payments/{$paymentKey}/receipt";
    }
}