document.addEventListener('DOMContentLoaded', function() {
    // Confirmation for delete actions
    const deleteLinks = document.querySelectorAll('.delete-link');
    
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const confirmMessage = this.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
            
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        });
    });
});