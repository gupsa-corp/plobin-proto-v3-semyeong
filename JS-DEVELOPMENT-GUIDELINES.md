# JavaScript 개발 가이드라인

## 핵심 원칙: 파일당 하나의 함수

이 프로젝트에서는 **JavaScript 파일의 가독성과 유지보수성을 위해 파일당 하나의 함수만을 포함하는 것을 원칙**으로 합니다.

### 파일 명명 규칙

```
[숫자접두사]-js-[기능명].blade.php
```

#### 예시:
- `400-js-dashboard-init.blade.php` - 대시보드 초기화
- `401-js-organization-status.blade.php` - 조직 상태 관리
- `402-js-organization-list.blade.php` - 조직 목록 관리
- `403-js-modal-manager.blade.php` - 모달 관리
- `404-js-project-manager.blade.php` - 프로젝트 관리

### 개발 워크플로우

1. **분석 단계**: 복잡한 기능을 여러 개의 단일 책임 함수로 분해
2. **개발 단계**: 각 함수를 별도 파일로 개발 및 테스트
3. **통합 단계**: 필요시 성능을 위해 관련 함수들을 하나의 파일로 통합
4. **문서화**: 각 함수의 역할과 책임을 명확히 문서화
