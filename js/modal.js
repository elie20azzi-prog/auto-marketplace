// Modal Management

/**
 * Open search modal
 */
function openSearchModal() {
    const modal = document.getElementById('searchModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        // Focus search input
        setTimeout(() => {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) searchInput.focus();
        }, 100);
    }
}

/**
 * Close search modal
 */
function closeSearchModal() {
    const modal = document.getElementById('searchModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        // Clear search and all filters
        const searchInput = document.getElementById('searchInput');
        if (searchInput) searchInput.value = '';
        
        // Clear multi-select checkboxes
        const makeCheckboxes = document.querySelectorAll('#makeDropdown .multi-select-checkbox');
        makeCheckboxes.forEach(cb => cb.checked = false);
        
        const yearCheckboxes = document.querySelectorAll('#yearDropdown .multi-select-checkbox');
        yearCheckboxes.forEach(cb => cb.checked = false);
        
        // Close dropdowns
        document.querySelectorAll('.multi-select-dropdown').forEach(dd => {
            dd.classList.remove('active');
            dd.previousElementSibling?.classList.remove('active');
        });
        
        // Update trigger texts
        const makeTrigger = document.getElementById('makeTrigger');
        if (makeTrigger) {
            makeTrigger.querySelector('.multi-select-text').textContent = 'All Makes';
            makeTrigger.style.color = 'var(--text-secondary)';
        }
        
        const yearTrigger = document.getElementById('yearTrigger');
        if (yearTrigger) {
            yearTrigger.querySelector('.multi-select-text').textContent = 'All Years';
            yearTrigger.style.color = 'var(--text-secondary)';
        }
        
        const maxPrice = document.getElementById('maxPrice');
        if (maxPrice) maxPrice.value = '';
        const results = document.getElementById('searchResults');
        if (results) results.innerHTML = '';
    }
}


/**
 * Initialize modal close on outside click and Escape key
 */
function initModalCloseOnClickOutside() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                if (modal.id === 'searchModal') {
                    closeSearchModal();
                }
            }
        });
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const searchModal = document.getElementById('searchModal');
            if (searchModal && searchModal.classList.contains('active')) {
                closeSearchModal();
            }
        }
    });
}

// Make functions globally available
window.openSearchModal = openSearchModal;
window.closeSearchModal = closeSearchModal;

