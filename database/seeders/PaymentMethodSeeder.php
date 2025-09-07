<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;
use App\Models\Organization;
use Illuminate\Support\Str;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기존 결제 수단 삭제
        PaymentMethod::truncate();

        // 조직 1의 기본 VISA 카드
        $organization = Organization::first();
        if ($organization) {
            PaymentMethod::create([
                'organization_id' => $organization->id,
                'billing_key' => 'billing_key_visa_1234_' . Str::random(10),
                'method_type' => 'card',
                'card_company' => 'VISA',
                'card_number' => '433012******1234',
                'card_type' => 'credit',
                'expiry_month' => '12',
                'expiry_year' => '2026',
                'is_default' => true,
                'is_active' => true,
                'toss_response' => [
                    'mId' => 'tvivarepublic',
                    'customerKey' => 'customer_' . $organization->id,
                    'authenticatedAt' => now()->toISOString(),
                    'method' => 'card',
                    'billingKey' => 'billing_key_visa_1234_' . Str::random(10),
                    'card' => [
                        'issuerCode' => '361',
                        'issuerName' => 'BC카드',
                        'acquirerCode' => '361', 
                        'acquirerName' => 'BC카드',
                        'number' => '433012******1234',
                        'cardType' => '체크',
                        'ownerType' => '개인'
                    ]
                ]
            ]);

            $this->command->info("✅ 조직 1 기본 VISA 카드 생성 완료");
        }

        // 스타터 조직의 VISA 카드
        $starterOrg = Organization::where('name', '스타터 조직')->first();
        if ($starterOrg) {
            PaymentMethod::create([
                'organization_id' => $starterOrg->id,
                'billing_key' => 'billing_key_visa_5678_' . Str::random(10),
                'method_type' => 'card',
                'card_company' => 'VISA',
                'card_number' => '433012******5678',
                'card_type' => 'credit',
                'expiry_month' => '08',
                'expiry_year' => '2025',
                'is_default' => true,
                'is_active' => true,
                'toss_response' => [
                    'mId' => 'tvivarepublic',
                    'customerKey' => 'customer_' . $starterOrg->id,
                    'authenticatedAt' => now()->toISOString(),
                    'method' => 'card',
                    'billingKey' => 'billing_key_visa_5678_' . Str::random(10),
                    'card' => [
                        'issuerCode' => '361',
                        'issuerName' => 'BC카드',
                        'acquirerCode' => '361',
                        'acquirerName' => 'BC카드', 
                        'number' => '433012******5678',
                        'cardType' => '신용',
                        'ownerType' => '개인'
                    ]
                ]
            ]);
        }

        // 취소된 조직의 삼성카드 (비활성화됨)
        $cancelledOrg = Organization::where('name', '취소된 조직')->first();
        if ($cancelledOrg) {
            PaymentMethod::create([
                'organization_id' => $cancelledOrg->id,
                'billing_key' => 'billing_key_samsung_9012_' . Str::random(10),
                'method_type' => 'card',
                'card_company' => '삼성카드',
                'card_number' => '433012******9012',
                'card_type' => 'credit',
                'expiry_month' => '03',
                'expiry_year' => '2027',
                'is_default' => true,
                'is_active' => false, // 구독 취소로 인해 비활성화
                'toss_response' => [
                    'mId' => 'tvivarepublic',
                    'customerKey' => 'customer_' . $cancelledOrg->id,
                    'authenticatedAt' => now()->subMonth()->toISOString(),
                    'method' => 'card',
                    'billingKey' => 'billing_key_samsung_9012_' . Str::random(10),
                    'card' => [
                        'issuerCode' => '365',
                        'issuerName' => '삼성카드',
                        'acquirerCode' => '365',
                        'acquirerName' => '삼성카드',
                        'number' => '433012******9012',
                        'cardType' => '신용',
                        'ownerType' => '개인'
                    ]
                ]
            ]);
        }

        // 추가 테스트 결제 수단들
        $this->createAdditionalPaymentMethods();

        $this->command->info("✅ 결제 수단 시딩 완료");
        $this->command->info("   - 조직 1: VISA 카드 (기본, 활성)");
        $this->command->info("   - 스타터 조직: VISA 카드 (기본, 활성)");
        $this->command->info("   - 취소된 조직: 삼성카드 (기본, 비활성)");
    }

    /**
     * 추가 테스트 결제 수단 생성
     */
    private function createAdditionalPaymentMethods(): void
    {
        $organization = Organization::first();
        if (!$organization) return;

        // 조직 1에 추가 카드 (기본이 아닌 보조 카드)
        PaymentMethod::create([
            'organization_id' => $organization->id,
            'billing_key' => 'billing_key_master_4321_' . Str::random(10),
            'method_type' => 'card',
            'card_company' => 'Mastercard',
            'card_number' => '433012******4321',
            'card_type' => 'credit',
            'expiry_month' => '06',
            'expiry_year' => '2025',
            'is_default' => false,
            'is_active' => true,
            'toss_response' => [
                'mId' => 'tvivarepublic',
                'customerKey' => 'customer_' . $organization->id,
                'authenticatedAt' => now()->subWeek()->toISOString(),
                'method' => 'card',
                'billingKey' => 'billing_key_master_4321_' . Str::random(10),
                'card' => [
                    'issuerCode' => '366',
                    'issuerName' => '신한카드',
                    'acquirerCode' => '366',
                    'acquirerName' => '신한카드',
                    'number' => '433012******4321',
                    'cardType' => '신용',
                    'ownerType' => '개인'
                ]
            ]
        ]);

        $this->command->info("   - 조직 1: Mastercard 추가 (보조, 활성)");
    }
}