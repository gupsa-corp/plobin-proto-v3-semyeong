<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PricingPlan;

class PricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기존 데이터 삭제
        PricingPlan::truncate();

        // 무료 플랜
        PricingPlan::create([
            'name' => '무료 플랜',
            'slug' => 'free',
            'description' => '개인 사용자를 위한 기본 기능',
            'type' => 'monthly',
            'monthly_price' => 0,
            'max_members' => 1,
            'max_projects' => 3,
            'max_storage_gb' => 1,
            'max_sheets' => 10,
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 1,
            'features' => [
                '개인 워크스페이스',
                '기본 프로젝트 관리',
                '1GB 스토리지',
                '최대 10개 시트',
                '기본 협업 기능'
            ]
        ]);

        // 스타터 플랜
        PricingPlan::create([
            'name' => '스타터 플랜',
            'slug' => 'starter',
            'description' => '소규모 팀을 위한 협업 솔루션',
            'type' => 'monthly',
            'monthly_price' => 29000,
            'max_members' => 5,
            'max_projects' => 10,
            'max_storage_gb' => 10,
            'max_sheets' => 50,
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 2,
            'features' => [
                '팀 워크스페이스',
                '고급 프로젝트 관리',
                '10GB 스토리지',
                '최대 50개 시트',
                '팀 협업 도구',
                '기본 분석 리포트'
            ]
        ]);

        // 프로 플랜
        PricingPlan::create([
            'name' => '프로 플랜',
            'slug' => 'pro',
            'description' => '성장하는 팀을 위한 전문 도구',
            'type' => 'monthly',
            'monthly_price' => 59000,
            'max_members' => 15,
            'max_projects' => 50,
            'max_storage_gb' => 50,
            'max_sheets' => 200,
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 3,
            'features' => [
                '다중 워크스페이스',
                '고급 프로젝트 템플릿',
                '50GB 스토리지',
                '최대 200개 시트',
                '고급 협업 도구',
                '상세 분석 및 리포트',
                '우선 고객 지원'
            ]
        ]);

        // 비즈니스 플랜
        PricingPlan::create([
            'name' => '비즈니스 플랜',
            'slug' => 'business',
            'description' => '대기업 및 엔터프라이즈급 기능',
            'type' => 'monthly',
            'monthly_price' => 99000,
            'max_members' => 50,
            'max_projects' => null, // 무제한
            'max_storage_gb' => 200,
            'max_sheets' => null, // 무제한
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 4,
            'features' => [
                '무제한 워크스페이스',
                '커스텀 프로젝트 템플릿',
                '200GB 스토리지',
                '무제한 시트',
                '엔터프라이즈 협업 도구',
                '고급 분석 대시보드',
                '전용 고객 지원',
                'SSO 연동',
                '감사 로그'
            ]
        ]);

        // 사용량 기반 플랜
        PricingPlan::create([
            'name' => '사용량 기반',
            'slug' => 'usage-based',
            'description' => '사용한 만큼만 지불하는 유연한 플랜',
            'type' => 'usage_based',
            'price_per_member' => 5000,
            'price_per_project' => 2000,
            'price_per_gb' => 500,
            'price_per_sheet' => 100,
            'free_members' => 2,
            'free_projects' => 5,
            'free_storage_gb' => 2,
            'free_sheets' => 20,
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 5,
            'features' => [
                '사용량 기반 과금',
                '멤버당 월 5,000원',
                '프로젝트당 월 2,000원',
                'GB당 월 500원',
                '시트당 월 100원',
                '무료 허용량 제공',
                '실시간 사용량 모니터링'
            ]
        ]);

        // 엔터프라이즈 플랜 (비활성)
        PricingPlan::create([
            'name' => '엔터프라이즈',
            'slug' => 'enterprise',
            'description' => '대규모 조직을 위한 맞춤형 솔루션',
            'type' => 'monthly',
            'monthly_price' => null, // 문의
            'max_members' => null, // 무제한
            'max_projects' => null, // 무제한
            'max_storage_gb' => null, // 무제한
            'max_sheets' => null, // 무제한
            'is_active' => false, // 비활성
            'is_featured' => false,
            'sort_order' => 6,
            'features' => [
                '완전 맞춤형 솔루션',
                '무제한 모든 기능',
                '온프레미스 배포',
                '전용 서버',
                '24/7 전담 지원',
                '커스텀 개발',
                '교육 및 컨설팅',
                '고급 보안 기능'
            ]
        ]);
    }
}
