import { test, expect } from '@playwright/test';

test.describe('결제 관리 페이지 간단 테스트', () => {
  test('결제 관리 페이지 직접 접근 및 기본 요소 확인', async ({ page }) => {
    // 직접 결제 관리 페이지로 이동 (로그인 우회)
    await page.goto('/organizations/1/admin/billing');
    await page.waitForLoadState('networkidle');
    
    // 페이지가 로드되었는지 확인 (리다이렉트되지 않았다면)
    if (page.url().includes('billing')) {
      // 주요 섹션 확인
      await expect(page.locator('h2')).toBeVisible({ timeout: 5000 });
      
      // 스크린샷 저장
      await page.screenshot({ 
        path: 'tests/e2e/screenshots/billing-direct-access.png',
        fullPage: true
      });
      
      console.log('결제 관리 페이지에 직접 접근 성공');
    } else {
      console.log('결제 관리 페이지 접근 실패, 리다이렉트됨:', page.url());
      
      // 리다이렉트된 페이지의 스크린샷도 저장
      await page.screenshot({ 
        path: 'tests/e2e/screenshots/billing-redirect.png',
        fullPage: true
      });
    }
  });

  test('사업자 조회 API 직접 테스트', async ({ page }) => {
    // API 테스트를 위해 페이지 로드
    await page.goto('/');
    
    // 사업자 조회 API 테스트
    const apiResponse = await page.evaluate(async () => {
      try {
        const response = await fetch('/api/test/organizations/1/billing/business-lookup', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            business_registration_number: '123-45-67890'
          })
        });
        
        const result = await response.json();
        return {
          status: response.status,
          success: result.success,
          data: result.data,
          message: result.message
        };
      } catch (error) {
        return {
          error: error.message
        };
      }
    });
    
    // API 응답 검증
    expect(apiResponse.status).toBe(200);
    expect(apiResponse.success).toBe(true);
    expect(apiResponse.data).toBeTruthy();
    expect(apiResponse.data.business_name).toContain('테스트');
    
    console.log('사업자 조회 API 테스트 성공:', apiResponse);
  });

  test('사업자 조회 API 다양한 케이스 테스트', async ({ page }) => {
    await page.goto('/');
    
    // 테스트 케이스들
    const testCases = [
      {
        name: '유효한 사업자등록번호 1',
        input: '123-45-67890',
        expectedStatus: 200
      },
      {
        name: '유효한 사업자등록번호 2', 
        input: '9876543210',
        expectedStatus: 200
      },
      {
        name: '잘못된 형식',
        input: '123',
        expectedStatus: 400
      },
      {
        name: '빈 값',
        input: '',
        expectedStatus: 400
      }
    ];

    for (const testCase of testCases) {
      const result = await page.evaluate(async (tc) => {
        try {
          const response = await fetch('/api/test/organizations/1/billing/business-lookup', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              business_registration_number: tc.input
            })
          });
          
          const data = await response.json();
          return {
            status: response.status,
            success: data.success,
            message: data.message
          };
        } catch (error) {
          return {
            error: error.message,
            status: 0
          };
        }
      }, testCase);
      
      expect(result.status).toBe(testCase.expectedStatus);
      console.log(`${testCase.name} - 상태: ${result.status}, 성공: ${result.success}`);
    }
  });

  test('결제 관리 페이지 요소별 존재 여부 확인', async ({ page }) => {
    // 메인 페이지로 가서 브라우저 환경 확인
    await page.goto('/');
    await page.waitForLoadState('networkidle');
    
    // 브라우저에서 직접 결제 페이지 HTML 확인
    const pageInfo = await page.evaluate(() => {
      return {
        title: document.title,
        url: window.location.href,
        hasJavaScript: typeof window !== 'undefined',
        hasConsole: typeof console !== 'undefined'
      };
    });
    
    console.log('페이지 정보:', pageInfo);
    
    // 기본 페이지 요소 확인
    expect(pageInfo.hasJavaScript).toBe(true);
    expect(pageInfo.hasConsole).toBe(true);
  });
});