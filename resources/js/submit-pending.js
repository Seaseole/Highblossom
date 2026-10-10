/**
 * Pending feedback for the auth forms, which boot without Alpine (see app.js).
 *
 * A button marked with data-submit-pending-button swaps its [data-idle-state]
 * parts for its [data-pending-state] part while the browser navigates away.
 */

/**
 * @param  HTMLButtonElement  button
 * @param  boolean  pending
 */
function setState(button, pending) {
    button.disabled = pending;
    button.setAttribute('aria-busy', pending ? 'true' : 'false');

    button.querySelectorAll('[data-idle-state]').forEach((el) => el.classList.toggle('hidden', pending));
    button.querySelectorAll('[data-pending-state]').forEach((el) => el.classList.toggle('hidden', !pending));
}

document.addEventListener('submit', (event) => {
    if (event.target instanceof HTMLFormElement) {
        event.target.querySelectorAll('[data-submit-pending-button]').forEach((button) => setState(button, true));
    }
});

// Back/Forward Cache restores the page with the button still disabled and no request in flight.
window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        document.querySelectorAll('[data-submit-pending-button]').forEach((button) => setState(button, false));
    }
});
