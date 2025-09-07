<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrganizationLicense;
use App\Models\Organization;
use Carbon\Carbon;

class OrganizationLicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기존 라이센스 삭제
        OrganizationLicense::truncate();

        // 조직 1의 Pro 플랜 라이센스 (활성)
        $organization = Organization::first();
        if ($organization) {
            // Pro 플랜 - 월간 구독
            OrganizationLicense::create([
                'organization_id' => $organization->id,
                'license_type' => 'pro',
                'license_name' => 'Pro Plan Monthly',
                'quantity' => 10,
                'unit_type' => 'seat',
                'unit_price' => 9900.00,
                'total_price' => 99000.00,
                'billing_cycle' => 'monthly',
                'starts_at' => Carbon::now()->subMonths(6),
                'expires_at' => Carbon::now()->addMonth(),
                'auto_renew' => true,
                'status' => 'active',
                'purchased_at' => Carbon::now()->subMonths(6),
                'metadata' => [
                    'features' => [
                        'unlimited_projects',
                        'advanced_analytics',
                        'priority_support',
                        'custom_integrations'
                    ],
                    'limits' => [
                        'storage_gb' => 100,
                        'api_calls_per_month' => 50000
                    ]
                ]
            ]);

            // 추가 스토리지 애드온
            OrganizationLicense::create([
                'organization_id' => $organization->id,
                'license_type' => 'addon',
                'license_name' => 'Additional Storage',
                'quantity' => 200,
                'unit_type' => 'gb',
                'unit_price' => 100.00,
                'total_price' => 20000.00,
                'billing_cycle' => 'monthly',
                'starts_at' => Carbon::now()->subMonths(3),
                'expires_at' => Carbon::now()->addMonth(),
                'auto_renew' => true,
                'status' => 'active',
                'purchased_at' => Carbon::now()->subMonths(3),
                'metadata' => [
                    'features' => ['additional_storage'],
                    'description' => '기본 100GB에 추가로 200GB 제공'
                ]
            ]);

            $this->command->info("✅ 조직 1 라이센스 생성 완료");
            $this->command->info("   - Pro Plan (10 seats, monthly)");
            $this->command->info("   - Additional Storage (200GB, monthly)");
        }

        // 스타터 조직의 Basic 플랜 라이센스 (활성)
        $starterOrg = Organization::where('name', '스타터 조직')->first();
        if ($starterOrg) {
            OrganizationLicense::create([
                'organization_id' => $starterOrg->id,
                'license_type' => 'basic',
                'license_name' => 'Basic Plan',
                'quantity' => 5,
                'unit_type' => 'seat',
                'unit_price' => 4900.00,
                'total_price' => 24500.00,
                'billing_cycle' => 'monthly',
                'starts_at' => Carbon::now()->subMonths(2),
                'expires_at' => Carbon::now()->addMonth(),
                'auto_renew' => true,
                'status' => 'active',
                'purchased_at' => Carbon::now()->subMonths(2),
                'metadata' => [
                    'features' => [
                        'basic_projects',
                        'standard_support',
                        'basic_analytics'
                    ],
                    'limits' => [
                        'storage_gb' => 50,
                        'api_calls_per_month' => 10000,
                        'projects_max' => 5
                    ]
                ]
            ]);

            $this->command->info("✅ 스타터 조직 라이센스 생성 완료");
            $this->command->info("   - Basic Plan (5 seats, monthly)");
        }

        // 취소된 조직의 라이센스 (취소됨)
        $cancelledOrg = Organization::where('name', '취소된 조직')->first();
        if ($cancelledOrg) {
            OrganizationLicense::create([
                'organization_id' => $cancelledOrg->id,
                'license_type' => 'pro',
                'license_name' => 'Pro Plan Monthly',
                'quantity' => 15,
                'unit_type' => 'seat',
                'unit_price' => 9900.00,
                'total_price' => 148500.00,
                'billing_cycle' => 'monthly',
                'starts_at' => Carbon::now()->subMonths(8),
                'expires_at' => Carbon::now()->subMonth(),
                'auto_renew' => false,
                'status' => 'cancelled',
                'purchased_at' => Carbon::now()->subMonths(8),
                'cancelled_at' => Carbon::now()->subMonth(),
                'cancellation_reason' => '고객 요청으로 인한 취소',
                'metadata' => [
                    'features' => [
                        'unlimited_projects',
                        'advanced_analytics',
                        'priority_support',
                        'custom_integrations'
                    ],
                    'cancellation_refund' => 0
                ]
            ]);

            $this->command->info("✅ 취소된 조직 라이센스 생성 완료");
            $this->command->info("   - Pro Plan (15 seats, cancelled)");
        }

        // 추가 테스트 라이센스들
        $this->createAdditionalLicenses();

        $this->command->info("✅ 라이센스 시딩 완료");
        $this->command->info("총 " . OrganizationLicense::count() . "개의 라이센스가 생성되었습니다.");
    }

    /**
     * 추가 테스트 라이센스 생성
     */
    private function createAdditionalLicenses(): void
    {
        $organization = Organization::first();
        if (!$organization) return;

        // 만료 예정 라이센스
        OrganizationLicense::create([
            'organization_id' => $organization->id,
            'license_type' => 'addon',
            'license_name' => 'Priority Support',
            'quantity' => 1,
            'unit_type' => 'feature',
            'unit_price' => 29000.00,
            'total_price' => 29000.00,
            'billing_cycle' => 'monthly',
            'starts_at' => Carbon::now()->subMonths(11),
            'expires_at' => Carbon::now()->addDays(15), // 15일 후 만료
            'auto_renew' => false,
            'status' => 'active',
            'purchased_at' => Carbon::now()->subMonths(11),
            'metadata' => [
                'features' => ['priority_support', '24_7_support', 'dedicated_manager'],
                'warning' => 'expiring_soon'
            ]
        ]);

        // 연간 결제 라이센스
        OrganizationLicense::create([
            'organization_id' => $organization->id,
            'license_type' => 'pro',
            'license_name' => 'Pro Plan Yearly',
            'quantity' => 5,
            'unit_type' => 'seat',
            'unit_price' => 99000.00, // 년간 단가
            'total_price' => 495000.00, // 5 seats * 99,000원
            'billing_cycle' => 'yearly',
            'starts_at' => Carbon::now()->subMonths(3),
            'expires_at' => Carbon::now()->addMonths(9),
            'auto_renew' => true,
            'status' => 'active',
            'purchased_at' => Carbon::now()->subMonths(3),
            'metadata' => [
                'features' => [
                    'unlimited_projects',
                    'advanced_analytics',
                    'priority_support',
                    'custom_integrations'
                ],
                'discount' => '20%_yearly_discount',
                'original_monthly_price' => 49500.00
            ]
        ]);

        // 일시정지된 라이센스
        $starterOrg = Organization::where('name', '스타터 조직')->first();
        if ($starterOrg) {
            OrganizationLicense::create([
                'organization_id' => $starterOrg->id,
                'license_type' => 'addon',
                'license_name' => 'Advanced Analytics',
                'quantity' => 1,
                'unit_type' => 'feature',
                'unit_price' => 19000.00,
                'total_price' => 19000.00,
                'billing_cycle' => 'monthly',
                'starts_at' => Carbon::now()->subMonths(4),
                'expires_at' => Carbon::now()->addMonth(),
                'auto_renew' => true,
                'status' => 'suspended',
                'purchased_at' => Carbon::now()->subMonths(4),
                'metadata' => [
                    'features' => ['advanced_reporting', 'custom_dashboards', 'data_export'],
                    'suspension_reason' => 'payment_failure',
                    'suspended_at' => Carbon::now()->subWeek()->toISOString()
                ]
            ]);
        }

        $this->command->info("   - Priority Support (expiring soon)");
        $this->command->info("   - Pro Plan Yearly (active)");
        $this->command->info("   - Advanced Analytics (suspended)");
    }
}
