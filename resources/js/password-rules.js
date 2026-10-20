/**
 * Live password requirement checklist.
 *
 * The rendered list is the specification: each item carries the rule it represents and
 * the number it was configured with, all of it written by
 * PasswordPolicy::checklist() in resources/views/components/ui/password-rules.blade.php.
 * Nothing here repeats a length or a character class, so saving a new policy on the
 * Security page changes this hint and the server's rejection in the same request — the
 * four per-view copies of `minLen` it replaces each had their own default of 8, which is
 * how a form whose validator wanted 12 characters came to promise 8.
 *
 * Vanilla, not Alpine: the auth screens boot no Alpine, so an x-data there is inert.
 */

const testers = {
    min: (value, limit) => characters(value) >= limit,
    max: (value, limit) => characters(value) <= limit,
    mixed: (value) => /\p{Lu}/u.test(value) && /\p{Ll}/u.test(value),
    numbers: (value) => /\d/.test(value),
    symbols: (value) => /[^\p{L}\p{N}]/u.test(value),
};

/**
 * Count characters the way Laravel's `min` and `max` rules do, so a hint can turn green
 * on the same keystroke the server would accept. Spreading on the surrogate pairs keeps
 * an emoji from counting as two.
 */
function characters(value) {
    return [...value].length;
}

function mark(item, met) {
    item.element.dataset.met = met ? 'true' : 'false';
    item.element.querySelector('[data-pw-marker-off]')?.classList.toggle('hidden', met);
    item.element.querySelector('[data-pw-marker-on]')?.classList.toggle('hidden', !met);
}

function evaluate(input, items) {
    const value = input.value;

    for (const item of items) {
        const tester = testers[item.rule];

        if (!tester) {
            continue;
        }

        // An empty box satisfies nothing, even the rules an empty string technically
        // passes: a blank field rendering "no more than 64 characters" in green reads as
        // progress on a form nobody has started yet.
        mark(item, value !== '' && tester(value, item.rule === 'max' ? item.max : item.min));
    }
}

/**
 * Bind one rendered checklist to its input.
 *
 * Called again after SPA navigation, so a bound list is skipped rather than attached
 * twice to the same element.
 */
function bind(list) {
    if (list.dataset.pwBound === '1') {
        return;
    }

    const input = document.getElementById(list.dataset.pwFor);

    if (!input) {
        return;
    }

    const items = [...list.querySelectorAll('[data-pw-rule]')].map((element) => ({
        element,
        rule: element.dataset.pwRule,
        min: Number(element.dataset.min || 0),
        max: Number(element.dataset.max || 0),
    }));

    list.dataset.pwBound = '1';

    // A repopulated field after a failed submit is not empty, so the list is evaluated
    // once up front instead of showing every requirement as unmet on a valid password.
    evaluate(input, items);

    input.addEventListener('input', () => evaluate(input, items));
}

function bindAll() {
    document.querySelectorAll('ul[data-pw-for]').forEach(bind);
}

bindAll();

// Livewire replaces the DOM around a page change, and the auth screens are reachable
// through wire:navigate links from the public site.
document.addEventListener('livewire:navigated', bindAll);
