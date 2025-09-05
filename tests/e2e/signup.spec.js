import { test, expect } from '@playwright/test';

// 빠른 필드 채우기 함수 - 0.3초 간격
async function fastFillField(page, selector, value, delay = 300) {
  await page.evaluate(({ selector, value }) => {
    const element = document.querySelector(selector);
    if (element) {
      element.value = value;
      // Alpine.js 반응성을 위한 이벤트 트리거
      element.dispatchEvent(new Event('input', { bubbles: true }));
      element.dispatchEvent(new Event('change', { bubbles: true }));
    }
  }, { selector, value });
  
  // 0.3초 대기
  await page.waitForTimeout(delay);
}

test.describe('회원가입 테스트', () => {
  test('빠른 필드 채우기로 회원가입', async ({ page }) => {
    // 회원가입 페이지로 이동
    await page.goto('/signup');
    
    // 페이지 로드 대기
    await page.waitForLoadState('networkidle');
    
    console.log('회원가입 폼 채우기 시작...');
    const startTime = Date.now();
    
    // 빠른 필드 채우기 (0.3초 간격)
    await fastFillField(page, 'input[name="first_name"]', '홍', 300);
    console.log('성 입력 완료');
    
    await fastFillField(page, 'input[name="last_name"]', '길동', 300);
    console.log('이름 입력 완료');
    
    await fastFillField(page, 'input[name="nickname"]', '테스트유저', 300);
    console.log('닉네임 입력 완료');
    
    await fastFillField(page, 'input[name="phone_number"]', '01012345678', 300);
    console.log('전화번호 입력 완료');
    
    await fastFillField(page, 'input[name="email"]', 'test@example.com', 300);
    console.log('이메일 입력 완료');
    
    await fastFillField(page, 'input[name="password"]', 'password123!', 300);
    console.log('비밀번호 입력 완료');
    
    await fastFillField(page, 'input[name="password_confirmation"]', 'password123!', 300);
    console.log('비밀번호 확인 입력 완료');
    
    const endTime = Date.now();
    console.log(`폼 채우기 완료 시간: ${(endTime - startTime) / 1000}초`);
    
    // 스크린샷 찍기
    await page.screenshot({ path: 'tests/e2e/screenshots/signup-filled.png' });
    
    // 제출 버튼 클릭
    await page.click('button[type="submit"]');
    
    // 성공 페이지나 대시보드로 리다이렉트 확인
    await page.waitForURL('**/dashboard', { timeout: 10000 });
    
    // 성공 확인
    expect(page.url()).toContain('dashboard');
    
    console.log('회원가입 테스트 완료!');
  });
  
  test('필드별 입력 속도 측정', async ({ page }) => {
    await page.goto('/signup');
    await page.waitForLoadState('networkidle');
    
    const fields = [
      { selector: 'input[name="first_name"]', value: '홍' },
      { selector: 'input[name="last_name"]', value: '길동' },
      { selector: 'input[name="nickname"]', value: '테스트유저' },
      { selector: 'input[name="phone_number"]', value: '01012345678' },
      { selector: 'input[name="email"]', value: 'speed@test.com' },
      { selector: 'input[name="password"]', value: 'password123!' },
      { selector: 'input[name="password_confirmation"]', value: 'password123!' }
    ];
    
    for (let i = 0; i < fields.length; i++) {
      const field = fields[i];
      const startTime = Date.now();
      
      await fastFillField(page, field.selector, field.value, 300);
      
      const endTime = Date.now();
      console.log(`필드 ${i+1} 입력 시간: ${endTime - startTime}ms`);
    }
  });
});