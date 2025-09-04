<script>
document.addEventListener('DOMContentLoaded', function() {
    const userButton = document.querySelector('.relative button');
    const dropdown = document.querySelector('.absolute.right-0');
    
    if (userButton && dropdown) {
        userButton.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });
        
        // 외부 클릭시 드롭다운 닫기
        document.addEventListener('click', function(e) {
            if (!userButton.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }
});
</script>