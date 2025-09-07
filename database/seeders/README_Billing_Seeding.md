# 결제 및 구독 데이터베이스 시딩 가이드

이 문서는 Plobin 플랫폼의 결제 및 구독 관련 데이터베이스 시딩에 대한 상세 가이드입니다.

## 📋 개요

결제 시스템은 다음 4개의 주요 테이블로 구성됩니다:
- `pricing_plans` - 요금제 정보
- `subscriptions` - 조직별 구독 정보  
- `payment_methods` - 결제 수단 정보
- `billing_histories` - 결제 내역

## 🚀 시딩 실행 방법

### 전체 데이터베이스 시딩
```bash
php artisan migrate:fresh --seed
```

### 결제 관련 데이터만 시딩
```bash
php artisan db:seed --class=BillingSeeder
```

### 개별 시더 실행
```bash
php artisan db:seed --class=PricingPlanSeeder
php artisan db:seed --class=SubscriptionSeeder
php artisan db:seed --class=PaymentMethodSeeder
php artisan db:seed --class=BillingHistorySeeder
```

## 📊 생성되는 데이터 구조

### 1. 요금제 (Pricing Plans) - 6개

| 플랜명 | Slug | 월 가격 | 멤버 수 | 프로젝트 | 스토리지 | 특징 |
|--------|------|---------|---------|----------|----------|------|
| 무료 플랜 | free | ₩0 | 1명 | 3개 | 1GB | 개인용 |
| 스타터 플랜 | starter | ₩29,000 | 5명 | 10개 | 10GB | 소규모 팀 |
| 프로 플랜 | pro | ₩59,000 | 15명 | 50개 | 50GB | 성장하는 팀 |
| 비즈니스 플랜 | business | ₩99,000 | 50명 | 무제한 | 200GB | 대기업 |
| 사용량 기반 | usage-based | 변동 | - | - | - | 유연한 과금 |
| 엔터프라이즈 | enterprise | 문의 | 무제한 | 무제한 | 무제한 | 맞춤형 |

### 2. 구독 (Subscriptions) - 4개 조직

#### 조직 1: "테스트 조직" (Pro 플랜)
- **플랜**: pro
- **상태**: active
- **월 가격**: ₩99,000
- **제한**: 50명, 무제한 프로젝트, 500GB
- **청구 기간**: 2024.03.15 - 2024.04.15

#### 조직 2: "스타터 조직" (Starter 플랜)  
- **플랜**: starter
- **상태**: active
- **월 가격**: ₩29,000
- **제한**: 5명, 10개 프로젝트, 10GB

#### 조직 3: "무료 조직" (Free 플랜)
- **플랜**: free
- **상태**: active
- **월 가격**: ₩0
- **제한**: 1명, 3개 프로젝트, 1GB

#### 조직 4: "취소된 조직" (Business 플랜 취소)
- **플랜**: business
- **상태**: cancelled
- **취소일**: 1주일 전
- **취소 사유**: "비용 절약을 위한 플랜 변경"

### 3. 결제 수단 (Payment Methods) - 4개

| 조직 | 카드사 | 카드 번호 | 만료일 | 상태 | 기본 |
|------|--------|-----------|--------|------|------|
| 테스트 조직 | VISA | ****1234 | 12/26 | 활성 | ✓ |
| 테스트 조직 | Mastercard | ****4321 | 06/25 | 활성 | - |
| 스타터 조직 | VISA | ****5678 | 08/25 | 활성 | ✓ |
| 취소된 조직 | 삼성카드 | ****9012 | 03/27 | 비활성 | ✓ |

### 4. 결제 내역 (Billing History) - 6건

#### 테스트 조직 (Pro 플랜)
- **2024.03.15**: ₩99,000 성공 (Pro 플랜 월간 구독)
- **2024.02.15**: ₩99,000 성공 (Pro 플랜 월간 구독)  
- **2024.01.15**: ₩99,000 성공 (Pro 플랜 월간 구독)

#### 스타터 조직
- **현재 월**: ₩29,000 성공 (Starter 플랜 월간 구독)

#### 취소된 조직  
- **지난 달**: ₩99,000 성공 (Business 플랜 마지막 결제)
- **1주일 전**: -₩49,500 부분환불 (중도 해지 환불)

## 🌐 테스트 방법

### 1. 웹 인터페이스 확인
결제 페이지에 접속하여 시딩된 데이터를 확인:
```
http://localhost:9100/organizations/1/admin/billing
```

### 2. 데이터베이스 직접 확인
```sql
-- 요금제 확인
SELECT name, slug, monthly_price, max_members FROM pricing_plans WHERE is_active = 1;

-- 구독 확인  
SELECT o.name, s.plan_name, s.status, s.monthly_price 
FROM subscriptions s 
JOIN organizations o ON s.organization_id = o.id;

-- 결제 수단 확인
SELECT o.name, pm.card_company, pm.card_number, pm.is_default, pm.is_active
FROM payment_methods pm
JOIN organizations o ON pm.organization_id = o.id;

-- 결제 내역 확인
SELECT o.name, bh.description, bh.amount, bh.status, bh.approved_at
FROM billing_histories bh
JOIN organizations o ON bh.organization_id = o.id
ORDER BY bh.approved_at DESC;
```

## 🔧 시더 구조

### 시더 파일들
- `PricingPlanSeeder.php` - 기본 요금제 생성
- `SubscriptionSeeder.php` - 조직별 구독 및 테스트 조직 생성
- `PaymentMethodSeeder.php` - 결제 수단 생성
- `BillingHistorySeeder.php` - 결제 내역 생성 (Toss Payments 형식)
- `BillingSeeder.php` - 통합 결제 시더 (독립 실행용)

### 실행 순서
1. PricingPlanSeeder (요금제)
2. SubscriptionSeeder (구독 + 테스트 조직)
3. PaymentMethodSeeder (결제 수단)
4. BillingHistorySeeder (결제 내역)

## 💡 주요 특징

### Toss Payments 연동 대응
- `billing_histories` 테이블에 실제 Toss Payments API 응답 형식의 JSON 데이터 포함
- `payment_methods` 테이블에 빌링키 및 카드 정보 저장
- 실제 결제 시스템과 동일한 데이터 구조

### 다양한 시나리오 커버
- ✅ 성공적인 월간 구독 결제
- ✅ 무료 플랜 사용
- ✅ 구독 취소 및 부분 환불
- ✅ 복수 결제 수단 관리
- ✅ 다양한 요금제 옵션

### 확장 가능한 구조
- 새로운 조직 및 구독 쉽게 추가 가능
- 요금제 변경 시나리오 테스트 지원
- 결제 실패, 재시도 등 추가 시나리오 확장 가능

## 🚨 주의사항

1. **데이터 초기화**: 각 시더는 `truncate()`를 사용하여 기존 데이터를 삭제합니다.
2. **의존성**: SubscriptionSeeder는 Organization 모델이 존재해야 실행됩니다.
3. **테스트 환경**: 프로덕션 환경에서는 실행하지 마세요.
4. **카드 정보**: 모든 카드 번호는 테스트용 마스킹된 데이터입니다.

## 📝 추가 시나리오 확장

필요한 경우 다음과 같은 시나리오를 추가할 수 있습니다:

```php
// 결제 실패 시나리오
BillingHistory::create([
    'status' => 'ABORTED',
    'description' => '카드 한도 초과로 인한 결제 실패',
    // ...
]);

// 플랜 업그레이드 시나리오  
Subscription::create([
    'plan_name' => 'business',
    'status' => 'active', 
    'monthly_price' => 99000,
    // ...
]);
```

이 시딩 시스템은 실제 운영 환경과 동일한 데이터 구조를 제공하여 개발 및 테스트 시 현실적인 환경을 제공합니다.