// Alpine configuration moved to site-alpine.js and admin.js


import './passkey-signin';
import './submit-pending';
import './password-rules';

import { Passkeys } from '@laravel/passkeys';
window.Passkeys = Passkeys;

// Simple vanilla JS helpers for public site
import {
    browserSupportsWebAuthn,
    startAuthentication,
    startRegistration,
} from '@simplewebauthn/browser'

window.browserSupportsWebAuthn = browserSupportsWebAuthn;
window.startAuthentication = startAuthentication;
window.startRegistration = startRegistration;
// Simple vanilla JS helpers for public site

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

// This bundle is a deferred module and loads once per document. wire:navigate swaps the
// whole <body> without re-running it, so nothing may be bound to an element directly:
// every handler is delegated from `document` and resolves its target at event time.
document.addEventListener('click', (e) => {
    const menuToggle = e.target.closest('[data-menu-toggle]');

    if (menuToggle) {
        document.querySelector('[data-menu]')?.classList.toggle('hidden');
    }

    const modalOpener = e.target.closest('[data-modal-open]');

    if (modalOpener) {
        document.getElementById(modalOpener.dataset.modalOpen)?.classList.remove('hidden');
    }

    const modalCloser = e.target.closest('[data-modal-close]');

    if (modalCloser) {
        modalCloser.closest('[data-modal]')?.classList.add('hidden');
    }
});

// Show/hide password toggles. The auth pages boot no Alpine, so this stays vanilla.

/**
 * Flip a password input and keep its toggle button honest about the state, so a
 * programmatic reveal (Generate) cannot leave the eye icon disagreeing with the
 * field next to it.
 *
 * @param  HTMLInputElement  input
 * @param  boolean  reveal
 */
function setRevealed(input, reveal) {
    input.type = reveal ? 'text' : 'password';

    const btn = document.querySelector(`[data-toggle-password="${input.id}"]`);

    if (!btn) {
        return;
    }

    btn.setAttribute('aria-label', reveal ? btn.dataset.hideLabel : btn.dataset.showLabel);
    btn.setAttribute('aria-expanded', reveal ? 'true' : 'false');
    btn.querySelector('[data-icon-eye]')?.classList.toggle('hidden', reveal);
    btn.querySelector('[data-icon-eye-off]')?.classList.toggle('hidden', !reveal);
}

document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-toggle-password]');
    if (!btn) return;

    const input = document.getElementById(btn.dataset.togglePassword);
    if (!input) return;

    const reveal = input.type === 'password';

    setRevealed(input, reveal);

    input.focus({ preventScroll: true });

    if (reveal) {
        input.setSelectionRange(input.value.length, input.value.length);
    }
});

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

        // Show the generated value so it can be copied, and mark the field
        // changed so Livewire/Alpine listeners on the same input still fire.
        setRevealed(input, true);
        input.dispatchEvent(new Event('input', { bubbles: true }));

        if (confirmInput) {
            confirmInput.value = password;
            confirmInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }
});

// Parallax for scroll-linked hero copy. Queried per event rather than cached, because the
// sections it looks for are replaced on every navigation.
const updateScrollText = () => {
    document.querySelectorAll('.js-scroll-with-image').forEach(section => {
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

