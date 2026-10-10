import { browserSupportsWebAuthn, startAuthentication, WebAuthnAbortService } from '@simplewebauthn/browser';

const OPTIONS_ROUTE = '/passkeys/login/options';
const SUBMIT_ROUTE = '/passkeys/login';
const CANCELLED_ERRORS = ['AbortError', 'NotAllowedError', 'UserCancelledError'];

const signinButton = () => document.getElementById('passkey-signin-btn');
const signinLabel = () => document.getElementById('passkey-signin-label');
const errorBox = () => document.getElementById('passkey-error');

/**
 * The passkey endpoints are session based, so mirror the token lookup used by
 * the Laravel passkey client: meta tag first, encrypted cookie second.
 */
function csrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');

    if (meta?.content) {
        return { header: 'X-CSRF-TOKEN', value: meta.content };
    }

    const cookie = document.cookie
        .split('; ')
        .find((entry) => entry.startsWith('XSRF-TOKEN='));

    return cookie
        ? { header: 'X-XSRF-TOKEN', value: decodeURIComponent(cookie.slice('XSRF-TOKEN='.length)) }
        : null;
}

async function request(route, body) {
    const headers = { Accept: 'application/json' };

    if (body) {
        headers['Content-Type'] = 'application/json';

        const token = csrfToken();

        if (token) {
            headers[token.header] = token.value;
        }
    }

    const response = await fetch(route, {
        method: body ? 'POST' : 'GET',
        headers,
        credentials: 'same-origin',
        body: body ? JSON.stringify(body) : undefined,
    });

    return { status: response.status, payload: await response.json().catch(() => ({})) };
}

function clearRecovery() {
    document
        .querySelectorAll('[data-passkey-recovery]')
        .forEach((panel) => panel.classList.add('hidden'));

    errorBox()?.classList.add('hidden');
}

function showRecovery(reason) {
    const panel = document.querySelector(`[data-passkey-recovery="${reason}"]`);

    if (!panel) {
        return false;
    }

    panel.classList.remove('hidden');

    return true;
}

function showError(message) {
    const box = errorBox();

    if (!box) {
        return;
    }

    box.textContent = message || box.dataset.default;
    box.classList.remove('hidden');
}

/**
 * An orphaned passkey cannot be recovered from the login page, so hand the user
 * to the password form they already have on screen.
 */
function invitePasswordSignIn() {
    const email = document.getElementById('email');

    email?.scrollIntoView({ block: 'center', behavior: 'smooth' });
    email?.focus({ preventScroll: true });
}

export async function signInWithPasskey() {
    const button = signinButton();
    const label = signinLabel();

    if (!button || !label || !browserSupportsWebAuthn()) {
        return;
    }

    clearRecovery();
    button.disabled = true;
    label.textContent = label.dataset.waiting;

    try {
        WebAuthnAbortService.cancelCeremony();

        const { payload: options } = await request(OPTIONS_ROUTE);
        const credential = await startAuthentication({ optionsJSON: options.options });
        const result = await request(SUBMIT_ROUTE, {
            credential,
            remember: document.querySelector('input[name="remember"]')?.checked ?? false,
        });

        if (result.status < 400 && result.payload.redirect) {
            window.location.href = result.payload.redirect;

            return;
        }

        const reason = result.status === 429 ? 'too_many_attempts' : result.payload.reason;

        if (reason && showRecovery(reason)) {
            if (reason === 'unrecognized_passkey') {
                invitePasswordSignIn();
            }

            return;
        }

        showError(result.payload.message);
    } catch (error) {
        if (!CANCELLED_ERRORS.includes(error?.name)) {
            showError(error?.message);
        }
    } finally {
        button.disabled = !browserSupportsWebAuthn();
        label.textContent = button.disabled ? label.dataset.unsupported : label.dataset.signIn;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const button = signinButton();

    if (!button) {
        return;
    }

    const label = signinLabel();

    button.disabled = !browserSupportsWebAuthn();
    label.textContent = button.disabled ? label.dataset.unsupported : label.dataset.signIn;

    document
        .querySelectorAll('[data-passkey-signin]')
        .forEach((target) => target.addEventListener('click', signInWithPasskey));
});
