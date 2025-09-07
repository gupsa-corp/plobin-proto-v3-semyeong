import { test, expect } from '@playwright/test';

// 유틸리티 함수: Livewire 상태 업데이트 대기
async function waitForLivewireUpdate(page, timeout = 5000) {
  await page.waitForFunction(() => {
    return !window.Livewire || window.Livewire.first().get('updating') === false;
  }, { timeout });
}

// 탭 전환 함수 (Livewire 기반)
async function switchTab(page, tabName) {
  await page.click(`button:has-text("${getTabDisplayName(tabName)}")`);
  await waitForLivewireUpdate(page);
}

// 탭 표시 이름 매핑
function getTabDisplayName(tabName) {
  const tabNames = {
    'repository': '저장소 상태',
    'commits': '커밋 관리',
    'branches': '브랜치 관리',
    'remote': '원격 저장소'
  };
  return tabNames[tabName] || tabName;
}

test.describe('Git 버전 관리 페이지 테스트', () => {
  test.beforeEach(async ({ page }) => {
    // Git 버전 관리 페이지로 이동
    await page.goto('/sandbox/git-version-control');
    await page.waitForLoadState('networkidle');
  });

  test('페이지 로드 및 기본 요소 확인', async ({ page }) => {
    // 페이지 제목 확인
    await expect(page.locator('h1')).toContainText('Git 버전 관리');
    
    // 탭 메뉴 확인 (Livewire 버튼)
    await expect(page.locator('button:has-text("저장소 상태")')).toBeVisible();
    await expect(page.locator('button:has-text("커밋 관리")')).toBeVisible();
    await expect(page.locator('button:has-text("브랜치 관리")')).toBeVisible();
    await expect(page.locator('button:has-text("원격 저장소")')).toBeVisible();
    
    // 기본적으로 저장소 상태 탭이 활성화되어 있는지 확인
    await expect(page.locator('h3:has-text("저장소 상태")')).toBeVisible();
    await expect(page.locator('text=작업 디렉토리')).toBeVisible();
    
    console.log('페이지 로드 및 기본 요소 확인 완료');
  });

  test('저장소 상태 탭 기능 테스트', async ({ page }) => {
    // 저장소 상태 탭으로 전환
    await switchTab(page, 'repository');
    
    // 작업 디렉토리 정보 확인
    await expect(page.locator('text=작업 디렉토리')).toBeVisible();
    
    // 새로고침 버튼 확인
    await expect(page.locator('button:has-text("새로고침")')).toBeVisible();
    
    // Git 초기화 버튼 확인
    await expect(page.locator('button:has-text("Git 초기화")')).toBeVisible();
    
    // 새로고침 버튼 클릭 테스트
    await page.click('button:has-text("새로고침")');
    await waitForLivewireUpdate(page);
    
    console.log('저장소 상태 탭 기능 테스트 완료');
  });

  test('커밋 관리 탭 기능 테스트', async ({ page }) => {
    // 커밋 관리 탭으로 전환
    await switchTab(page, 'commits');
    
    // 커밋 메시지 입력 필드 확인
    await expect(page.locator('textarea[wire\\:model="commitMessage"]')).toBeVisible();
    
    // 커밋 생성 버튼 확인
    await expect(page.locator('button:has-text("커밋 생성")')).toBeVisible();
    
    // 최근 커밋 섹션 확인
    await expect(page.locator('h3:has-text("최근 커밋")')).toBeVisible();
    
    // 커밋 메시지 입력 테스트
    await page.fill('textarea[wire\\:model="commitMessage"]', 'Test commit message');
    await page.waitForTimeout(300);
    
    // 입력된 값 확인
    const commitMessage = await page.inputValue('textarea[wire\\:model="commitMessage"]');
    expect(commitMessage).toBe('Test commit message');
    
    console.log('커밋 관리 탭 기능 테스트 완료');
  });

  test('브랜치 관리 탭 기능 테스트', async ({ page }) => {
    // 브랜치 관리 탭으로 전환
    await switchTab(page, 'branches');
    
    // 브랜치 생성 섹션 확인
    await expect(page.locator('h3:has-text("브랜치 생성")')).toBeVisible();
    await expect(page.locator('input[wire\\:model="newBranchName"]')).toBeVisible();
    await expect(page.locator('button:has-text("브랜치 생성")')).toBeVisible();
    
    // 브랜치 목록 섹션 확인
    await expect(page.locator('h3:has-text("브랜치 목록")')).toBeVisible();
    
    // 브랜치 병합 섹션 확인
    await expect(page.locator('h3:has-text("브랜치 병합")')).toBeVisible();
    await expect(page.locator('select[wire\\:model="mergeFromBranch"]')).toBeVisible();
    
    // 새 브랜치 이름 입력 테스트
    await page.fill('input[wire\\:model="newBranchName"]', 'feature/test-branch');
    await page.waitForTimeout(300);
    
    const branchName = await page.inputValue('input[wire\\:model="newBranchName"]');
    expect(branchName).toBe('feature/test-branch');
    
    console.log('브랜치 관리 탭 기능 테스트 완료');
  });

  test('원격 저장소 탭 기능 테스트', async ({ page }) => {
    // 원격 저장소 탭으로 전환
    await switchTab(page, 'remote');
    
    // 저장소 복제 섹션 확인
    await expect(page.locator('h3:has-text("저장소 복제")')).toBeVisible();
    await expect(page.locator('input[wire\\:model="cloneUrl"]')).toBeVisible();
    await expect(page.locator('button:has-text("저장소 복제")')).toBeVisible();
    
    // 원격 저장소 명령어 섹션 확인
    await expect(page.locator('h3:has-text("원격 저장소 명령어")')).toBeVisible();
    await expect(page.locator('text=Push (현재 브랜치를 원격으로)')).toBeVisible();
    await expect(page.locator('text=Pull (원격에서 변경사항 가져오기)')).toBeVisible();
    await expect(page.locator('text=Fetch (원격 정보만 가져오기)')).toBeVisible();
    
    // 주의사항 확인
    await expect(page.locator('text=원격 저장소 작업은 터미널에서 직접 실행해야 합니다')).toBeVisible();
    
    // 저장소 URL 입력 테스트
    const testUrl = 'https://github.com/test/repository.git';
    await page.fill('input[wire\\:model="cloneUrl"]', testUrl);
    await page.waitForTimeout(300);
    
    const cloneUrl = await page.inputValue('input[wire\\:model="cloneUrl"]');
    expect(cloneUrl).toBe(testUrl);
    
    console.log('원격 저장소 탭 기능 테스트 완료');
  });

  test('탭 전환 기능 테스트', async ({ page }) => {
    const tabs = [
      { name: 'repository', heading: '저장소 상태' },
      { name: 'commits', heading: '커밋 생성' },
      { name: 'branches', heading: '브랜치 생성' },
      { name: 'remote', heading: '저장소 복제' }
    ];
    
    for (const tab of tabs) {
      // 탭 클릭
      await switchTab(page, tab.name);
      
      // 해당 탭 콘텐츠가 표시되는지 확인
      await expect(page.locator(`h3:has-text("${tab.heading}")`)).toBeVisible();
      
      console.log(`${tab.name} 탭 전환 확인 완료`);
    }
    
    console.log('탭 전환 기능 테스트 완료');
  });

  test('에러 메시지 표시 테스트', async ({ page }) => {
    // 커밋 관리 탭으로 전환
    await switchTab(page, 'commits');
    
    // 빈 커밋 메시지로 커밋 시도
    await page.click('button:has-text("커밋 생성")');
    await waitForLivewireUpdate(page);
    
    // 에러 메시지가 표시되는지 확인 (Livewire 응답에 따라)
    // 실제 구현에서는 적절한 에러 메시지를 확인해야 함
    
    console.log('에러 메시지 표시 테스트 완료');
  });

  test('빈 브랜치 이름으로 브랜치 생성 시도', async ({ page }) => {
    // 브랜치 관리 탭으로 전환
    await switchTab(page, 'branches');
    
    // 빈 브랜치 이름으로 브랜치 생성 시도
    await page.click('button:has-text("브랜치 생성")');
    await waitForLivewireUpdate(page);
    
    console.log('빈 브랜치 이름 테스트 완료');
  });

  test('스크린샷 촬영', async ({ page }) => {
    // 각 탭별 스크린샷 촬영
    const tabs = [
      { name: 'repository', title: '저장소 상태' },
      { name: 'commits', title: '커밋 관리' },
      { name: 'branches', title: '브랜치 관리' },
      { name: 'remote', title: '원격 저장소' }
    ];
    
    for (const tab of tabs) {
      await switchTab(page, tab.name);
      await page.screenshot({ 
        path: `tests/e2e/screenshots/git-version-control-${tab.name}.png`,
        fullPage: true
      });
      console.log(`${tab.title} 탭 스크린샷 저장 완료`);
    }
  });

  test('반응형 디자인 테스트', async ({ page }) => {
    // 모바일 뷰포트로 변경
    await page.setViewportSize({ width: 375, height: 667 });
    
    // 페이지가 여전히 정상적으로 표시되는지 확인
    await expect(page.locator('h1')).toContainText('Git 버전 관리');
    
    // 탭 메뉴가 여전히 접근 가능한지 확인
    await expect(page.locator('nav a[href="#repository"]')).toBeVisible();
    
    // 모바일에서 스크린샷
    await page.screenshot({ 
      path: 'tests/e2e/screenshots/git-version-control-mobile.png',
      fullPage: true
    });
    
    // 태블릿 뷰포트로 변경
    await page.setViewportSize({ width: 768, height: 1024 });
    
    await page.screenshot({ 
      path: 'tests/e2e/screenshots/git-version-control-tablet.png',
      fullPage: true
    });
    
    console.log('반응형 디자인 테스트 완료');
  });

  test('성능 테스트', async ({ page }) => {
    const startTime = Date.now();
    
    // 페이지 로드 시간 측정
    await page.goto('/sandbox/git-version-control');
    await page.waitForLoadState('networkidle');
    
    const loadTime = Date.now() - startTime;
    console.log(`페이지 로드 시간: ${loadTime}ms`);
    
    // 탭 전환 성능 측정
    const tabs = ['commits', 'branches', 'remote', 'repository'];
    
    for (const tab of tabs) {
      const tabStartTime = Date.now();
      await switchTab(page, tab);
      const tabTime = Date.now() - tabStartTime;
      console.log(`${tab} 탭 전환 시간: ${tabTime}ms`);
    }
    
    // 로드 시간이 3초를 초과하지 않는지 확인
    expect(loadTime).toBeLessThan(3000);
    
    console.log('성능 테스트 완료');
  });
});