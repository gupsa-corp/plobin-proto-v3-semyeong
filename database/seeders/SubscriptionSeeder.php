<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscription;
use App\Models\Organization;
use Carbon\Carbon;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기존 구독 데이터 삭제
        Subscription::truncate();

        // 조직 1에 대한 Pro 플랜 구독 (현재 활성)
        $organization = Organization::first();
        if ($organization) {
            $currentPeriodStart = Carbon::create(2024, 3, 15);
            $currentPeriodEnd = Carbon::create(2024, 4, 15);

            Subscription::create([
                'organization_id' => $organization->id,
                'plan_name' => 'pro',
                'status' => 'active',
                'monthly_price' => 99000,
                'max_members' => 50,
                'max_projects' => null, // 무제한
                'max_storage_gb' => 500,
                'current_period_start' => $currentPeriodStart,
                'current_period_end' => $currentPeriodEnd,
                'next_billing_date' => $currentPeriodEnd,
            ]);

            $this->command->info("✅ Pro 플랜 구독 생성 완료 (조직 ID: {$organization->id})");
        }

        // 추가 테스트 조직들을 위한 다양한 구독 상태 생성
        $this->createTestOrganizations();
    }

    /**
     * 테스트용 조직들과 다양한 구독 상태 생성
     */
    private function createTestOrganizations(): void
    {
        // 테스트 조직 2 - Starter 플랜
        $org2 = Organization::updateOrCreate(
            ['name' => '스타터 조직'],
            [
                'name' => '스타터 조직',
                'description' => '스타터 플랜을 사용하는 테스트 조직',
                'user_id' => 1,
                'status' => 'active',
                'members_count' => 3
            ]
        );

        Subscription::create([
            'organization_id' => $org2->id,
            'plan_name' => 'starter',
            'status' => 'active',
            'monthly_price' => 29000,
            'max_members' => 5,
            'max_projects' => 10,
            'max_storage_gb' => 10,
            'current_period_start' => Carbon::now()->startOfMonth(),
            'current_period_end' => Carbon::now()->endOfMonth(),
            'next_billing_date' => Carbon::now()->endOfMonth(),
        ]);

        // 테스트 조직 3 - 무료 플랜
        $org3 = Organization::updateOrCreate(
            ['name' => '무료 조직'],
            [
                'name' => '무료 조직',
                'description' => '무료 플랜을 사용하는 테스트 조직',
                'user_id' => 1,
                'status' => 'active',
                'members_count' => 1
            ]
        );

        Subscription::create([
            'organization_id' => $org3->id,
            'plan_name' => 'free',
            'status' => 'active',
            'monthly_price' => 0,
            'max_members' => 1,
            'max_projects' => 3,
            'max_storage_gb' => 1,
            'current_period_start' => Carbon::now()->startOfMonth(),
            'current_period_end' => Carbon::now()->endOfMonth(),
            'next_billing_date' => Carbon::now()->endOfMonth(),
        ]);

        // 테스트 조직 4 - 취소된 구독
        $org4 = Organization::updateOrCreate(
            ['name' => '취소된 조직'],
            [
                'name' => '취소된 조직',
                'description' => '구독을 취소한 테스트 조직',
                'user_id' => 1,
                'status' => 'active',
                'members_count' => 2
            ]
        );

        Subscription::create([
            'organization_id' => $org4->id,
            'plan_name' => 'business',
            'status' => 'cancelled',
            'monthly_price' => 99000,
            'max_members' => 50,
            'max_projects' => null,
            'max_storage_gb' => 200,
            'current_period_start' => Carbon::now()->subMonth()->startOfMonth(),
            'current_period_end' => Carbon::now()->subMonth()->endOfMonth(),
            'next_billing_date' => Carbon::now()->subMonth()->endOfMonth(),
            'cancelled_at' => Carbon::now()->subWeek(),
            'cancellation_reason' => '비용 절약을 위한 플랜 변경',
        ]);

        $this->command->info("✅ 추가 테스트 조직 및 구독 생성 완료");
        $this->command->info("   - 스타터 조직 (Starter 플랜)");
        $this->command->info("   - 무료 조직 (Free 플랜)");
        $this->command->info("   - 취소된 조직 (취소된 Business 플랜)");
    }
}