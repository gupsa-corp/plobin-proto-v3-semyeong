<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * 결제 및 구독 관련 데이터만 시딩하는 별도 시더
     * 사용법: php artisan db:seed --class=BillingSeeder
     */
    public function run(): void
    {
        $this->command->info('🚀 결제 및 구독 데이터 시딩 시작...');

        // 요금제 시딩 (이미 존재하지 않는 경우에만)
        $this->call([
            PricingPlanSeeder::class,
            SubscriptionSeeder::class,
            PaymentMethodSeeder::class,
            BillingHistorySeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('💳 결제 및 구독 데이터 시딩 완료!');
        $this->command->info('');
        $this->command->info('📊 생성된 데이터 요약:');
        $this->command->info('  • 요금제: 6개 (무료, 스타터, 프로, 비즈니스, 사용량기반, 엔터프라이즈)');
        $this->command->info('  • 구독: 4개 조직 (활성, 무료, 스타터, 취소됨)');
        $this->command->info('  • 결제수단: 4개 카드 (VISA, Mastercard, 삼성카드)');
        $this->command->info('  • 결제내역: 6건 (성공 5건, 부분환불 1건)');
        $this->command->info('');
        $this->command->info('🌐 테스트 URL: http://localhost:9100/organizations/1/admin/billing');
    }
}