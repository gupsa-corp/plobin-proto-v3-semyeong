{{-- 조직 데이터 로딩 함수 --}}
<script>
/**
 * 조직 데이터를 로드합니다
 */
async function loadOrganizationData() {
    if (!this.organizationId) return;

    try {
        this.organizationData = await loadOrganizationDetail(this.organizationId);
    } catch (error) {
        console.error('조직 데이터 로드 실패:', error);
        this.hasError = true;
        this.errorMessage = '조직 정보를 불러올 수 없습니다.';
    }
}
</script>