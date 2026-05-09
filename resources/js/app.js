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

    const scrollImageSections = document.querySelectorAll('.js-scroll-with-image');
    if (scrollImageSections.length) {
        const updateScrollText = () => {
            scrollImageSections.forEach(section => {
                const text = section.querySelector('[data-scroll-text]');
                if (!text) return;

                const rect = section.getBoundingClientRect();
                const windowHeight = window.innerHeight;
                const progress = (windowHeight - rect.top) / (windowHeight + rect.height);
                const offset = Math.max(-32, Math.min(32, progress * 32));

                text.style.transform = `translate3d(0, ${offset}px, 0)`;
            });
        };

        window.addEventListener('scroll', updateScrollText, { passive: true });
        window.addEventListener('resize', updateScrollText);
        updateScrollText();
    }
});
