import { test, expect } from '@playwright/test';

test.describe('결제 관리 페이지 테스트', () => {
  // 테스트 전 로그인 처리
  test.beforeEach(async ({ page }) => {
    // 로그인 페이지로 이동
    await page.goto('/login');
    await page.waitForLoadState('networkidle');
    
    // 로그인 정보 입력 (테스트 계정) - ID 기반 선택자 사용
    await page.fill('#email', 'admin@gupsa.com');
    await page.fill('#password', 'password123');
    
    // 로그인 버튼 클릭
    await page.click('button:has-text("로그인")');
    
    // 대시보드로 리다이렉트 대기
    await page.waitForURL('**/dashboard', { timeout: 10000 });
    
    // 결제 관리 페이지로 이동
    await page.goto('/organizations/1/admin/billing');
    await page.waitForLoadState('networkidle');
  });

  test('결제 관리 페이지 기본 요소 확인', async ({ page }) => {
    // 페이지 제목 확인
    expect(await page.title()).toContain('결제 관리');
    
    // 주요 섹션 확인
    await expect(page.locator('h2:has-text("결제 관리")')).toBeVisible();
    await expect(page.locator('h3:has-text("월간 사용량")')).toBeVisible();
    await expect(page.locator('h3:has-text("이번 달 요약")')).toBeVisible();
    await expect(page.locator('h3:has-text("결제 수단")')).toBeVisible();
    await expect(page.locator('h3:has-text("결제 내역")')).toBeVisible();
    
    // 스크린샷 저장
    await page.screenshot({ 
      path: 'tests/e2e/screenshots/billing-main.png',
      fullPage: true
    });
  });

  test('월간 사용량 정보 확인', async ({ page }) => {
    // 활성 멤버 정보 확인
    const activeMember = page.locator('text=활성 멤버');
    await expect(activeMember).toBeVisible();
    
    // 프로젝트 정보 확인
    const projects = page.locator('text=프로젝트');
    await expect(projects).toBeVisible();
    
    // 스토리지 정보 확인
    const storage = page.locator('text=스토리지');
    await expect(storage).toBeVisible();
    
    // 사용량 수치가 표시되는지 확인
    await expect(page.locator('text=/\\d+ \\/ \\d+/')).toHaveCount({ min: 2 });
    await expect(page.locator('text=/\\d+GB \\/ \\d+GB/')).toBeVisible();
  });

  test('결제 수단 관리 기능 확인', async ({ page }) => {
    // 결제 수단 추가 버튼 확인
    const addPaymentButton = page.locator('button:has-text("결제 수단 추가")');
    await expect(addPaymentButton).toBeVisible();
    
    // 기존 결제 수단 확인 (VISA 카드가 있다면)
    const existingCard = page.locator('text=VISA');
    if (await existingCard.isVisible()) {
      await expect(page.locator('text=**** **** **** 1234')).toBeVisible();
      await expect(page.locator('text=만료일')).toBeVisible();
      await expect(page.locator('button:has-text("편집")')).toBeVisible();
      await expect(page.locator('button:has-text("삭제")')).toBeVisible();
    }
    
    // 결제 수단 추가 버튼 클릭 테스트
    await addPaymentButton.click();
    await page.waitForTimeout(1000); // 모달이 열릴 시간 대기
    
    // 스크린샷 저장
    await page.screenshot({ 
      path: 'tests/e2e/screenshots/billing-payment-methods.png',
      fullPage: true
    });
  });

  test('결제 내역 테이블 확인', async ({ page }) => {
    // 결제 내역 테이블 확인
    const billingTable = page.locator('table');
    await expect(billingTable).toBeVisible();
    
    // 테이블 헤더 확인
    await expect(page.locator('text=날짜')).toBeVisible();
    await expect(page.locator('text=설명')).toBeVisible();
    await expect(page.locator('text=금액')).toBeVisible();
    await expect(page.locator('text=상태')).toBeVisible();
    await expect(page.locator('text=영수증')).toBeVisible();
    
    // 결제 내역이 있다면 확인
    const firstRow = billingTable.locator('tbody tr').first();
    if (await firstRow.isVisible()) {
      await expect(firstRow.locator('td').first()).toContainText(/\d{4}\.\d{2}\.\d{2}/);
      await expect(firstRow.locator('button:has-text("다운로드")')).toBeVisible();
    }
    
    // 페이지네이션 확인
    await expect(page.locator('button:has-text("이전")')).toBeVisible();
    await expect(page.locator('button:has-text("다음")')).toBeVisible();
    
    // 필터 드롭다운 확인
    const filterDropdown = page.locator('select, [role="combobox"]');
    await expect(filterDropdown).toBeVisible();
  });

  test('영수증 다운로드 버튼 확인', async ({ page }) => {
    // 영수증 다운로드 버튼 확인
    const receiptButton = page.locator('button:has-text("영수증 다운로드")');
    await expect(receiptButton).toBeVisible();
    
    // 버튼이 비활성화된 상태인지 확인 (초기 상태)
    if (await receiptButton.isDisabled()) {
      console.log('영수증 다운로드 버튼이 비활성화 상태입니다.');
    } else {
      // 버튼이 활성화된 경우 클릭 테스트
      await receiptButton.click();
      await page.waitForTimeout(1000);
    }
  });

  test('요금제 변경 기능 확인', async ({ page }) => {
    // 요금제 변경 버튼 확인
    const changePlanButton = page.locator('button:has-text("요금제 변경")');
    await expect(changePlanButton).toBeVisible();
    
    // 현재 플랜 정보 확인
    const currentPlan = page.locator('h3:has-text("Pro 플랜")');
    if (await currentPlan.isVisible()) {
      await expect(page.locator('text=활성')).toBeVisible();
      await expect(page.locator('text=월 요금')).toBeVisible();
      await expect(page.locator('text=₩99,000')).toBeVisible();
    }
    
    // 요금제 변경 버튼 클릭 시 알림 처리
    page.on('dialog', async dialog => {
      console.log('알림 메시지:', dialog.message());
      await dialog.accept();
    });
    
    await changePlanButton.click();
    await page.waitForTimeout(1000);
    
    // 스크린샷 저장
    await page.screenshot({ 
      path: 'tests/e2e/screenshots/billing-plan-change.png',
      fullPage: true
    });
  });

  test('이번 달 요약 정보 확인', async ({ page }) => {
    // 활성 사용자 정보 확인
    const activeUsers = page.locator('text=활성 사용자');
    await expect(activeUsers).toBeVisible();
    await expect(page.locator('text=/\\d+명/')).toBeVisible();
    
    // 추가 스토리지 정보 확인
    const additionalStorage = page.locator('text=추가 스토리지');
    await expect(additionalStorage).toBeVisible();
    
    // 총 금액 확인
    const totalAmount = page.locator('text=총 금액');
    await expect(totalAmount).toBeVisible();
    await expect(page.locator('text=₩99,000')).toBeVisible();
  });

  test('반응형 디자인 확인', async ({ page }) => {
    // 모바일 뷰포트로 변경
    await page.setViewportSize({ width: 375, height: 667 });
    await page.waitForTimeout(500);
    
    // 주요 요소들이 여전히 보이는지 확인
    await expect(page.locator('h2:has-text("결제 관리")')).toBeVisible();
    await expect(page.locator('button:has-text("요금제 변경")')).toBeVisible();
    
    // 모바일 스크린샷 저장
    await page.screenshot({ 
      path: 'tests/e2e/screenshots/billing-mobile.png',
      fullPage: true
    });
    
    // 데스크톱 뷰포트로 복구
    await page.setViewportSize({ width: 1280, height: 720 });
  });
});

test.describe('사업자 정보 관리 테스트', () => {
  test.beforeEach(async ({ page }) => {
    // 로그인 처리 (동일)
    await page.goto('/login');
    await page.waitForLoadState('networkidle');
    
    await page.fill('#email', 'admin@gupsa.com');
    await page.fill('#password', 'password123');
    await page.click('button:has-text("로그인")');
    await page.waitForURL('**/dashboard', { timeout: 10000 });
    
    // 결제 관리 페이지로 이동
    await page.goto('/organizations/1/admin/billing');
    await page.waitForLoadState('networkidle');
  });

  test('사업자 정보 입력 모달 확인', async ({ page }) => {
    // 사업자 정보 관련 버튼이나 링크 찾기
    // 실제 구현에 따라 선택자를 수정해야 할 수 있습니다
    const businessInfoButton = page.locator('button:has-text("사업자 정보"), button:has-text("사업자등록"), a:has-text("사업자")');
    
    if (await businessInfoButton.isVisible()) {
      await businessInfoButton.click();
      await page.waitForTimeout(1000);
      
      // 사업자 정보 입력 폼 확인
      await expect(page.locator('input[name="business_name"]')).toBeVisible();
      await expect(page.locator('input[name="business_registration_number"]')).toBeVisible();
      await expect(page.locator('input[name="representative_name"]')).toBeVisible();
      
      // 스크린샷 저장
      await page.screenshot({ 
        path: 'tests/e2e/screenshots/business-info-form.png',
        fullPage: true
      });
    } else {
      console.log('사업자 정보 관련 버튼을 찾을 수 없습니다. UI 구현 확인이 필요합니다.');
    }
  });

  test('사업자등록번호 조회 기능 테스트', async ({ page }) => {
    // 사업자등록번호 조회 API 호출 테스트
    // 실제 API 엔드포인트가 구현되었을 때 테스트
    
    const testBusinessNumber = '123-45-67890';
    
    // API 응답 모킹 (필요시)
    await page.route('**/api/business-lookup**', async route => {
      const json = {
        success: true,
        data: {
          business_name: '테스트 회사',
          representative_name: '홍길동',
          business_type: '서비스업',
          address: '서울시 강남구 테스트로 123'
        }
      };
      await route.fulfill({ json });
    });
    
    // 사업자등록번호 입력 및 조회 버튼 클릭
    if (await page.locator('input[name="business_registration_number"]').isVisible()) {
      await page.fill('input[name="business_registration_number"]', testBusinessNumber);
      
      const lookupButton = page.locator('button:has-text("조회"), button:has-text("검색")');
      if (await lookupButton.isVisible()) {
        await lookupButton.click();
        await page.waitForTimeout(2000);
        
        // 조회 결과 확인
        await expect(page.locator('text=테스트 회사')).toBeVisible();
        await expect(page.locator('text=홍길동')).toBeVisible();
        
        // 스크린샷 저장
        await page.screenshot({ 
          path: 'tests/e2e/screenshots/business-lookup-result.png',
          fullPage: true
        });
      }
    }
  });

  test('사업자 정보 저장 기능 테스트', async ({ page }) => {
    // 사업자 정보 저장 API 모킹
    await page.route('**/api/organizations/*/business-info', async route => {
      const json = {
        success: true,
        message: '사업자 정보가 성공적으로 저장되었습니다.',
        data: {
          id: 1,
          business_name: '테스트 회사',
          business_registration_number: '1234567890',
          representative_name: '홍길동'
        }
      };
      await route.fulfill({ json });
    });
    
    // 사업자 정보 입력 폼이 있는 경우
    if (await page.locator('input[name="business_name"]').isVisible()) {
      await page.fill('input[name="business_name"]', '테스트 회사');
      await page.fill('input[name="business_registration_number"]', '1234567890');
      await page.fill('input[name="representative_name"]', '홍길동');
      await page.fill('input[name="business_type"]', '서비스업');
      await page.fill('input[name="address"]', '서울시 강남구 테스트로 123');
      
      // 저장 버튼 클릭
      const saveButton = page.locator('button:has-text("저장"), button[type="submit"]');
      await saveButton.click();
      await page.waitForTimeout(1000);
      
      // 성공 메시지 확인
      await expect(page.locator('text=성공적으로 저장')).toBeVisible();
    }
  });
});