# ApiClient 사용법

## 개요

AuthManager와 통합된 API 클라이언트로 인증이 필요한 HTTP 요청을 편리하게 처리

## 파일 위치

- ApiClient: 300-page-service/300-common/000-api-client.blade.php

## 주요 기능

### HTTP 메소드

- window.apiClient.get(endpoint, params)
- window.apiClient.post(endpoint, data)
- window.apiClient.put(endpoint, data)
- window.apiClient.delete(endpoint)

### 자동 처리

- Authorization 헤더 자동 추가
- 401 에러시 자동 로그아웃 처리
- JSON 요청/응답 처리
- 에러 상태 일관된 처리

### 사용 예시

```javascript
// GET 요청
const users = await window.apiClient.get('/api/users');

// POST 요청
const newUser = await window.apiClient.post('/api/users', {
    name: 'John Doe',
    email: 'john@example.com'
});

// PUT 요청
const updatedUser = await window.apiClient.put('/api/users/1', {
    name: 'Jane Doe'
});

// DELETE 요청
await window.apiClient.delete('/api/users/1');
```

## AuthManager와의 연동

ApiClient는 AuthManager의 토큰을 자동으로 사용하며, 인증 상태 변화에 반응

## 주의사항

- AuthManager가 먼저 로드되어야 함
- 토큰 만료시 자동 로그아웃 처리됨
- 모든 요청은 JSON 형태로 처리됨
