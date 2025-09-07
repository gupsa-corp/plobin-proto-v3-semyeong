import { test, expect } from '@playwright/test';

// 유틸리티 함수: Livewire 상태 업데이트 대기
async function waitForLivewireUpdate(page, timeout = 3000) {
  await page.waitForTimeout(1000); // 간단한 대기 방식 사용
}

test.describe('API 생성기 페이지 테스트', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/sandbox/api-creator');
    await page.waitForLoadState('networkidle');
  });

  test('페이지 로드 및 기본 UI 확인', async ({ page }) => {
    // 페이지 제목 확인
    await expect(page.locator('h1')).toContainText('API 생성기');
    
    // 주요 섹션 확인
    await expect(page.locator('h2:has-text("API 템플릿")')).toBeVisible();
    await expect(page.locator('h2:has-text("API 정보")')).toBeVisible();
    await expect(page.locator('h2:has-text("API 코드")')).toBeVisible();
    await expect(page.locator('h2:has-text("API 작업")')).toBeVisible();
    
    // 템플릿 선택 드롭다운 확인
    await expect(page.locator('select:has(option:has-text("기본 GET API"))')).toBeVisible();
    
    // 버튼들 확인
    await expect(page.locator('button:has-text("템플릿 로드")')).toBeVisible();
    await expect(page.locator('button:has-text("문법 검사")')).toBeVisible();
    await expect(page.locator('button:has-text("API 저장")')).toBeVisible();
    await expect(page.locator('button:has-text("문서 생성")')).toBeVisible();
    
    console.log('페이지 로드 및 기본 UI 확인 완료');
  });

  test('템플릿 선택 및 로드 기능 테스트', async ({ page }) => {
    // 기본 GET API 템플릿 선택
    await page.selectOption('select[wire\\:model="selectedTemplate"]', 'basic_get');
    await page.click('button:has-text("템플릿 로드")');
    
    await waitForLivewireUpdate(page);
    
    // 코드 영역에 템플릿이 로드되었는지 확인
    const codeTextarea = page.locator('textarea[wire\\:model="apiCode"]');
    const codeContent = await codeTextarea.inputValue();
    expect(codeContent).toContain('Controller');
    expect(codeContent).toContain('<?php');
    
    // CRUD 리소스 템플릿 테스트
    await page.selectOption('select[wire\\:model="selectedTemplate"]', 'crud_resource');
    await page.click('button:has-text("템플릿 로드")');
    
    await waitForLivewireUpdate(page);
    
    const crudContent = await codeTextarea.inputValue();
    expect(crudContent).toContain('index');
    expect(crudContent).toContain('store');
    expect(crudContent).toContain('show');
    expect(crudContent).toContain('update');
    expect(crudContent).toContain('destroy');
    
    console.log('템플릿 선택 및 로드 기능 테스트 완료');
  });

  test('API 정보 입력 기능 테스트', async ({ page }) => {
    // API 이름 입력
    await page.fill('input[wire\\:model="apiName"]', 'Product');
    
    // HTTP 메서드 선택
    await page.selectOption('select[wire\\:model="httpMethod"]', 'GET');
    
    // API 라우트 확인 (자동 생성)
    const routeInput = page.locator('input[wire\\:model="apiRoute"]');
    await page.waitForTimeout(500); // Livewire 업데이트 대기
    const routeValue = await routeInput.inputValue();
    expect(routeValue).toContain('product');
    
    // API 설명 입력
    await page.fill('textarea[wire\\:model="apiDescription"]', '상품 관리 API');
    
    // 컨트롤러 생성 체크박스 테스트
    await page.check('input[wire\\:model="generateController"]');
    
    // 미리보기 섹션 확인
    await expect(page.locator('text=ProductController')).toBeVisible();
    await expect(page.locator('text=실제 컨트롤러 파일이 생성됩니다')).toBeVisible();
    
    console.log('API 정보 입력 기능 테스트 완료');
  });

  test('코드 입력 및 문법 검사 테스트', async ({ page }) => {
    // 간단한 PHP 코드 입력
    const testCode = `<?php
namespace App\\Http\\Controllers\\Api;
use App\\Http\\Controllers\\Controller;
class TestController extends Controller {
    public function index() {
        return response()->json(['message' => 'Hello']);
    }
}`;
    
    await page.fill('textarea[wire\\:model="apiCode"]', testCode);
    
    // 문법 검사 버튼 클릭
    await page.click('button:has-text("문법 검사")');
    await waitForLivewireUpdate(page);
    
    // 성공 메시지 확인
    await expect(page.locator('.bg-green-100')).toBeVisible();
    
    console.log('코드 입력 및 문법 검사 테스트 완료');
  });

  test('API 저장 기능 테스트', async ({ page }) => {
    // 필수 정보 입력
    await page.fill('input[wire\\:model="apiName"]', 'TestAPI');
    await page.fill('textarea[wire\\:model="apiDescription"]', 'Test API for E2E testing');
    
    const testCode = `<?php
class TestAPIController {
    public function index() {
        return ['test' => true];
    }
}`;
    await page.fill('textarea[wire\\:model="apiCode"]', testCode);
    
    // API 저장 버튼 클릭
    await page.click('button:has-text("API 저장")');
    await waitForLivewireUpdate(page);
    
    // 성공 메시지 확인
    await expect(page.locator('.bg-green-100')).toBeVisible();
    const successMessage = await page.locator('.bg-green-100').textContent();
    expect(successMessage).toContain('성공적으로 저장되었습니다');
    
    console.log('API 저장 기능 테스트 완료');
  });

  test('API 문서 생성 기능 테스트', async ({ page }) => {
    // 필수 정보 입력
    await page.fill('input[wire\\:model="apiName"]', 'DocumentTest');
    await page.fill('textarea[wire\\:model="apiDescription"]', 'Document generation test API');
    await page.selectOption('select[wire\\:model="httpMethod"]', 'RESOURCE');
    
    const testCode = `<?php
class DocumentTestController {
    public function index() { return []; }
}`;
    await page.fill('textarea[wire\\:model="apiCode"]', testCode);
    
    // API 문서 생성 버튼 클릭
    await page.click('button:has-text("문서 생성")');
    await waitForLivewireUpdate(page);
    
    // 성공 메시지 확인
    await expect(page.locator('.bg-green-100')).toBeVisible();
    const successMessage = await page.locator('.bg-green-100').textContent();
    expect(successMessage).toContain('API 문서가 생성되었습니다');
    
    console.log('API 문서 생성 기능 테스트 완료');
  });

  test('폼 유효성 검사 테스트', async ({ page }) => {
    // 빈 상태에서 저장 시도
    await page.click('button:has-text("API 저장")');
    await waitForLivewireUpdate(page);
    
    // 에러 메시지 확인
    await expect(page.locator('.text-red-600')).toBeVisible();
    
    // API 이름만 입력하고 저장 시도
    await page.fill('input[wire\\:model="apiName"]', 'Te'); // 3자 미만
    await page.click('button:has-text("API 저장")');
    await waitForLivewireUpdate(page);
    
    // 최소 길이 에러 메시지 확인
    await expect(page.locator('text=최소 3자 이상')).toBeVisible();
    
    console.log('폼 유효성 검사 테스트 완료');
  });

  test('HTTP 메서드별 기능 테스트', async ({ page }) => {
    const methods = ['GET', 'POST', 'PUT', 'DELETE', 'RESOURCE'];
    
    for (const method of methods) {
      await page.selectOption('select[wire\\:model="httpMethod"]', method);
      await page.waitForTimeout(200);
      
      // 미리보기에서 메서드 확인
      await expect(page.locator(`text=${method}`)).toBeVisible();
      
      console.log(`${method} 메서드 테스트 완료`);
    }
    
    console.log('HTTP 메서드별 기능 테스트 완료');
  });

  test('컨트롤러 생성 옵션 테스트', async ({ page }) => {
    await page.fill('input[wire\\:model="apiName"]', 'OptionTest');
    
    // 체크박스 해제 상태
    await page.uncheck('input[wire\\:model="generateController"]');
    await expect(page.locator('text=샌드박스에만 저장됩니다')).toBeVisible();
    
    // 체크박스 체크 상태
    await page.check('input[wire\\:model="generateController"]');
    await expect(page.locator('text=실제 컨트롤러 파일이 생성됩니다')).toBeVisible();
    
    console.log('컨트롤러 생성 옵션 테스트 완료');
  });

  test('스크린샷 촬영', async ({ page }) => {
    // 전체 페이지 스크린샷
    await page.screenshot({ 
      path: 'tests/e2e/screenshots/api-creator-full.png',
      fullPage: true
    });
    
    // 템플릿을 로드한 상태에서 스크린샷
    await page.selectOption('select[wire\\:model="selectedTemplate"]', 'crud_resource');
    await page.click('button:has-text("템플릿 로드")');
    await waitForLivewireUpdate(page);
    
    await page.screenshot({ 
      path: 'tests/e2e/screenshots/api-creator-with-template.png',
      fullPage: true
    });
    
    console.log('스크린샷 촬영 완료');
  });

  test('성능 테스트', async ({ page }) => {
    const startTime = Date.now();
    
    // 페이지 로드 시간
    await page.goto('/sandbox/api-creator');
    await page.waitForLoadState('networkidle');
    
    const loadTime = Date.now() - startTime;
    console.log(`페이지 로드 시간: ${loadTime}ms`);
    
    // 템플릿 로드 성능
    const templateStartTime = Date.now();
    await page.selectOption('select[wire\\:model="selectedTemplate"]', 'crud_resource');
    await page.click('button:has-text("템플릿 로드")');
    await waitForLivewireUpdate(page);
    const templateTime = Date.now() - templateStartTime;
    console.log(`템플릿 로드 시간: ${templateTime}ms`);
    
    // 성능 기준 확인
    expect(loadTime).toBeLessThan(5000); // 5초 이내 로드
    expect(templateTime).toBeLessThan(3000); // 3초 이내 템플릿 로드
    
    console.log('성능 테스트 완료');
  });
});