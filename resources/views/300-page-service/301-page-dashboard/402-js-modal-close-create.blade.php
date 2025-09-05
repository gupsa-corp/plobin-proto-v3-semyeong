{{-- 조직 생성 모달 닫기 함수 --}}
<script>
/**
 * 조직 생성 모달을 닫는 함수
 */
function closeCreateModal() {
    this.isCreateModalOpen = false;
    this.resetForm();
    ModalUtils.hideModal('createOrgModal');
}
</script>