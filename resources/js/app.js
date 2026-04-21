// Simple vanilla JS helpers for public site
document.addEventListener('DOMContentLoaded', () => {
    // Mobile menu toggle
    const menuToggle = document.querySelector('[data-menu-toggle]');
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            document.querySelector('[data-menu]').classList.toggle('hidden');
        });
    }

    // Modal handlers
    document.querySelectorAll('[data-modal-open]').forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.dataset.modalOpen;
            document.getElementById(modalId).classList.remove('hidden');
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(button => {
        button.addEventListener('click', () => {
            button.closest('[data-modal]').classList.add('hidden');
        });
    });
});
