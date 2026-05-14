// Simple vanilla JS helpers for public site
import {
    browserSupportsWebAuthn,
    startAuthentication,
    startRegistration,
} from '@simplewebauthn/browser'

window.browserSupportsWebAuthn = browserSupportsWebAuthn;
window.startAuthentication = startAuthentication;
window.startRegistration = startRegistration;

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

    // Password Generator
    window.generateSecurePassword = function(length = 16) {
        const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lowercase = 'abcdefghijklmnopqrstuvwxyz';
        const numbers = '0123456789';
        const symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
        
        let password = '';
        // Ensure at least one of each to meet typical "mixedCase", "letters", "numbers", "symbols" rules
        password += uppercase[Math.floor(Math.random() * uppercase.length)];
        password += lowercase[Math.floor(Math.random() * lowercase.length)];
        password += numbers[Math.floor(Math.random() * numbers.length)];
        password += symbols[Math.floor(Math.random() * symbols.length)];
        
        const all = uppercase + lowercase + numbers + symbols;
        for (let i = password.length; i < length; i++) {
            password += all[Math.floor(Math.random() * all.length)];
        }
        
        // Shuffle
        return password.split('').sort(() => 0.5 - Math.random()).join('');
    };

    // Global listener for generate password buttons
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-generate-password]');
        if (!btn) return;

        const targetId = btn.dataset.generatePassword;
        const confirmId = btn.dataset.confirmTarget;
        const input = document.getElementById(targetId);
        const confirmInput = confirmId ? document.getElementById(confirmId) : null;

        if (input) {
            const password = window.generateSecurePassword(16);
            input.value = password;
            input.type = 'text'; // Show it initially so they can see it? Or keep it hidden?
            // Usually good to show it or have a toggle.
            
            if (confirmInput) {
                confirmInput.value = password;
            }

            // Dispatch input event for frameworks like Livewire/Alpine
            input.dispatchEvent(new Event('input', { bubbles: true }));
            if (confirmInput) confirmInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });
});

