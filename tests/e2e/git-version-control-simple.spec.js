import { test, expect } from '@playwright/test';

test.describe('Git 버전 관리 페이지 간단 테스트', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/sandbox/git-version-control');
    await page.waitForLoadState('networkidle');
  });

  test('페이지 로드 및 기본 UI 확인', async ({ page }) => {
    // 페이지 제목 확인
    await expect(page.locator('h1')).toContainText('Git 버전 관리');
    
    // 탭 버튼들이 표시되는지 확인
    await expect(page.locator('button:has-text("저장소 상태")')).toBeVisible();
    await expect(page.locator('button:has-text("커밋 관리")')).toBeVisible();
    await expect(page.locator('button:has-text("브랜치 관리")')).toBeVisible();
    await expect(page.locator('button:has-text("원격 저장소")')).toBeVisible();
    
    // 기본 저장소 상태 탭 내용 확인
    await expect(page.locator('text=작업 디렉토리')).toBeVisible();
    await expect(page.locator('button:has-text("새로고침")')).toBeVisible();
    await expect(page.locator('button:has-text("Git 초기화")')).toBeVisible();
    
    console.log('페이지 로드 및 기본 UI 확인 완료');
  });

  test('탭 버튼 클릭 반응성 확인', async ({ page }) => {
    // 각 탭 버튼이 클릭 가능한지 확인
    await page.click('button:has-text("커밋 관리")');
    await page.waitForTimeout(1000);
    await expect(page.locator('textarea[placeholder*="커밋 메시지"]')).toBeVisible();
    
    await page.click('button:has-text("브랜치 관리")');
    await page.waitForTimeout(1000);
    await expect(page.locator('input[placeholder*="브랜치 이름"]')).toBeVisible();
    
    await page.click('button:has-text("원격 저장소")');
    await page.waitForTimeout(1000);
    await expect(page.locator('input[placeholder*="github.com"]')).toBeVisible();
    
    await page.click('button:has-text("저장소 상태")');
    await page.waitForTimeout(1000);
    await expect(page.locator('text=작업 디렉토리')).toBeVisible();
    
    console.log('탭 버튼 클릭 반응성 확인 완료');
  });

  test('폼 요소 입력 확인', async ({ page }) => {
    // 커밋 메시지 입력 테스트
    await page.click('button:has-text("커밋 관리")');
    await page.waitForTimeout(1000);
    
    const commitTextarea = page.locator('textarea[placeholder*="커밋 메시지"]');
    await commitTextarea.fill('테스트 커밋 메시지');
    await expect(commitTextarea).toHaveValue('테스트 커밋 메시지');
    
    // 브랜치 이름 입력 테스트
    await page.click('button:has-text("브랜치 관리")');
    await page.waitForTimeout(1000);
    
    const branchInput = page.locator('input[placeholder*="브랜치 이름"]');
    await branchInput.fill('feature/test-branch');
    await expect(branchInput).toHaveValue('feature/test-branch');
    
    // 저장소 URL 입력 테스트
    await page.click('button:has-text("원격 저장소")');
    await page.waitForTimeout(1000);
    
    const urlInput = page.locator('input[placeholder*="github.com"]');
    await urlInput.fill('https://github.com/test/repo.git');
    await expect(urlInput).toHaveValue('https://github.com/test/repo.git');
    
    console.log('폼 요소 입력 확인 완료');
  });

  test('버튼 요소 존재 확인', async ({ page }) => {
    // 저장소 상태 탭의 버튼들
    await expect(page.locator('button:has-text("새로고침")')).toBeVisible();
    await expect(page.locator('button:has-text("Git 초기화")')).toBeVisible();
    
    // 커밋 관리 탭의 버튼들
    await page.click('button:has-text("커밋 관리")');
    await page.waitForTimeout(1000);
    await expect(page.locator('button:has-text("커밋 생성")')).toBeVisible();
    
    // 브랜치 관리 탭의 버튼들
    await page.click('button:has-text("브랜치 관리")');
    await page.waitForTimeout(1000);
    await expect(page.locator('button:has-text("브랜치 생성")')).toBeVisible();
    await expect(page.locator('button:has-text("병합")')).toBeVisible();
    
    // 원격 저장소 탭의 버튼들
    await page.click('button:has-text("원격 저장소")');
    await page.waitForTimeout(1000);
    await expect(page.locator('button:has-text("저장소 복제")')).toBeVisible();
    
    console.log('버튼 요소 존재 확인 완료');
  });

  test('스크린샷 촬영', async ({ page }) => {
    // 전체 페이지 스크린샷
    await page.screenshot({ 
      path: 'tests/e2e/screenshots/git-version-control-full.png',
      fullPage: true
    });
    
    // 각 탭별 스크린샷
    const tabs = [
      { name: '저장소 상태', filename: 'repository' },
      { name: '커밋 관리', filename: 'commits' },
      { name: '브랜치 관리', filename: 'branches' },  
      { name: '원격 저장소', filename: 'remote' }
    ];
    
    for (const tab of tabs) {
      await page.click(`button:has-text("${tab.name}")`);
      await page.waitForTimeout(1000);
      await page.screenshot({ 
        path: `tests/e2e/screenshots/git-version-control-${tab.filename}.png`,
        fullPage: true
      });
    }
    
    console.log('스크린샷 촬영 완료');
  });

  test('반응형 레이아웃 확인', async ({ page }) => {
    // 데스크톱 크기
    await page.setViewportSize({ width: 1920, height: 1080 });
    await expect(page.locator('h1')).toContainText('Git 버전 관리');
    
    // 태블릿 크기
    await page.setViewportSize({ width: 768, height: 1024 });
    await expect(page.locator('h1')).toContainText('Git 버전 관리');
    await expect(page.locator('button:has-text("저장소 상태")')).toBeVisible();
    
    // 모바일 크기
    await page.setViewportSize({ width: 375, height: 667 });
    await expect(page.locator('h1')).toContainText('Git 버전 관리');
    await expect(page.locator('button:has-text("저장소 상태")')).toBeVisible();
    
    console.log('반응형 레이아웃 확인 완료');
  });

  test('성능 측정', async ({ page }) => {
    const startTime = Date.now();
    
    // 페이지 로드 시간
    await page.goto('/sandbox/git-version-control');
    await page.waitForLoadState('networkidle');
    
    const loadTime = Date.now() - startTime;
    console.log(`페이지 로드 시간: ${loadTime}ms`);
    
    // 탭 전환 시간
    const tabSwitchTimes = [];
    const tabs = ['커밋 관리', '브랜치 관리', '원격 저장소', '저장소 상태'];
    
    for (const tab of tabs) {
      const tabStartTime = Date.now();
      await page.click(`button:has-text("${tab}")`);
      await page.waitForTimeout(500);
      const tabTime = Date.now() - tabStartTime;
      tabSwitchTimes.push(tabTime);
      console.log(`${tab} 탭 전환 시간: ${tabTime}ms`);
    }
    
    const avgTabTime = tabSwitchTimes.reduce((a, b) => a + b, 0) / tabSwitchTimes.length;
    console.log(`평균 탭 전환 시간: ${avgTabTime.toFixed(0)}ms`);
    
    // 성능 기준 확인
    expect(loadTime).toBeLessThan(5000); // 5초 이내 로드
    expect(avgTabTime).toBeLessThan(1000); // 평균 1초 이내 탭 전환
    
    console.log('성능 측정 완료');
  });
});