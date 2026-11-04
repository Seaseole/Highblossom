/**
 * THE SCORING BENCH — the marks engine behind /admin/settings and /admin/profile.
 *
 * One claim, computed from the page itself: a control whose current content
 * differs from what the shop has stored is *marked*, and a mark is drawn down
 * the left of its row. Everything the module paints is a consequence of that
 * comparison — the per-group counts, the ticks in the index rail, the sentence
 * in the commit foot — so there is one source of truth and it is the DOM.
 *
 * Why it works this way:
 *
 * - The baseline is read once, at boot, from the values the server rendered. It
 *   is not a copy of the database, and nothing about a stored value is ever
 *   written into the page for the browser to compare against. That matters most
 *   on the System Config tab, where printing a stored value would put a secret
 *   in the HTML.
 * - A control inside a row the server already flagged with a validation error
 *   gets no baseline at all. After a refused save the box is showing rejected
 *   input rather than stored input, so that row must keep reading as
 *   uncommitted until it is fixed and committed, not go quiet because the
 *   browser mistook the rejected value for the truth.
 * - A control the module has never seen gets no baseline either, which is the
 *   right answer for the eight Alpine repeaters: a slide, a fact tile or an
 *   announcement that was just added does not exist in the database yet.
 * - Order is a value. A repeater row that has moved out of the position it was
 *   read in is marked, because the order is what the site plays them in and
 *   Alpine re-uses the element when it moves it.
 *
 * There is no loop here. Work is coalesced into a single animation frame per
 * burst of events, the frame is cancelled when the page leaves, and the one
 * timer that does exist is the elapsed "last set" readout, which sleeps until
 * the next whole minute and stops while the document is hidden. The page is
 * complete and correct without any of this: with JavaScript off the sheet still
 * renders every value and still saves, and the counts simply are not there.
 */

/**
 * The comparable value of a control.
 *
 * A file input cannot be read back, so its contribution is how many files are
 * sitting in it — which is exactly the question, because picking a logo is a
 * change whether or not the person has pressed Save yet.
 *
 * @param {HTMLInputElement|HTMLSelectElement|HTMLTextAreaElement} control
 * @returns {string}
 */
function sheetValueOf(control) {
    if (control.type === 'file') {
        return `files:${control.files ? control.files.length : 0}`;
    }

    if (control.type === 'checkbox' || control.type === 'radio') {
        return control.checked ? '1' : '0';
    }

    return control.value;
}

/**
 * @param {Element} scope
 * @returns {Element[]}
 */
function sheetList(selector, scope) {
    return Array.prototype.slice.call((scope || document).querySelectorAll(selector));
}

/**
 * Formats the one sentence the foot makes, and the one the rail makes.
 *
 * @param {number} marks
 * @param {number} groups
 * @returns {string}
 */
function sheetMarksSentence(marks, groups) {
    if (marks === 0) {
        return 'Nothing marked. Everything on this sheet is set.';
    }

    const noun = marks === 1 ? 'mark' : 'marks';
    const place =
        groups === 0
            ? ''
            : groups === 1
              ? ' on 1 group'
              : ` on ${groups} groups`;

    return `${marks} ${noun}${place} not yet set.`;
}

/**
 * Turns a real timestamp into the words a person reads beside it.
 *
 * Past a day it stops counting and names the date, because "312 hours ago" is
 * not information about a settings page.
 *
 * @param {Date} at
 * @param {Date} now
 * @returns {string}
 */
function sheetElapsedText(at, now) {
    const minutes = Math.floor((now.getTime() - at.getTime()) / 60000);

    if (Number.isNaN(minutes) || minutes < 0) {
        return 'not recorded';
    }

    if (minutes < 1) {
        return 'just now';
    }

    if (minutes < 60) {
        return `${minutes} min ago`;
    }

    if (minutes < 1440) {
        return `${Math.floor(minutes / 60)} h ago`;
    }

    return at.toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' });
}

/**
 * What the sheet says when a box refuses to be right.
 *
 * The browser has its own sentence for every constraint, and it shows it in its
 * own bubble, over the top of this design, in its own words. The rest of this
 * project already settled that argument for the auth screens: `novalidate` and
 * first-party sentences. Here the browser's check is kept, because the server
 * does not validate these fields and the bubble is the only thing between a
 * mistyped address and a saved one — but the words are the sheet's, and they
 * appear in the row that is wrong rather than in a floating chip.
 *
 * @param {HTMLInputElement|HTMLTextAreaElement} control
 * @returns {string}
 */
function sheetInvalidText(control) {
    const v = control.validity;

    if (v.valueMissing) {
        return 'This has to be filled in.';
    }

    if (v.typeMismatch) {
        return control.type === 'email'
            ? 'That is not an email address. It needs a name and a domain, like name@example.com.'
            : 'That is not a link. It has to start with https://';
    }

    if (v.patternMismatch) {
        return 'That does not match the format asked for.';
    }

    if (v.tooLong) {
        return `${control.value.length} characters is more than the ${control.maxLength} this box takes.`;
    }

    if (v.rangeUnderflow) {
        return `Use ${control.min} or more.`;
    }

    if (v.rangeOverflow) {
        return `Use ${control.max} or less.`;
    }

    if (v.badInput) {
        return 'That is not a value this box takes.';
    }

    return control.validationMessage;
}

/**
 * @param {Element} root
 * @returns {undefined|Function}
 */
function bootSheet(root) {
    if (!root) {
        return undefined;
    }

    /** @type {WeakMap<Element, string>} */
    const baselines = new WeakMap();

    /** @type {WeakMap<Element, number>} */
    const orders = new WeakMap();

    /**
     * Reads the page once and remembers what it said. Only ever called at boot
     * and on a full re-read, because a second seed would quietly accept whatever
     * is on screen as the stored truth.
     */
    const seed = () => {
        sheetList('.hb-field', root).forEach((field) => {
            const refused = field.hasAttribute('data-error');

            sheetList('input, select, textarea', field).forEach((control) => {
                if (refused) {
                    baselines.delete(control);

                    return;
                }

                baselines.set(control, sheetValueOf(control));
            });
        });

        sheetList('[data-sheet-list]', root).forEach((list) => {
            sheetList(':scope > [data-sheet-order]', list).forEach((row, index) => {
                orders.set(row, index);
            });
        });
    };

    seed();

    /**
     * A row is marked when any control in it disagrees with what it was read as,
     * or when it has never been read at all.
     *
     * @param {Element} field
     * @returns {boolean}
     */
    const fieldIsMarked = (field) =>
        sheetList('input, select, textarea', field).some((control) => {
            if (!baselines.has(control)) {
                return true;
            }

            return baselines.get(control) !== sheetValueOf(control);
        });

    /**
     * @param {Element} block
     * @returns {number}
     */
    const countIn = (block) => leafFields(block).filter(fieldIsMarked).length;

    /**
     * @param {Element} form
     * @returns {number}
     */
    const groupsIn = (form) => sheetList('.hb-block', form).filter((b) => b.hasAttribute('data-marked')).length;

    let frame = 0;
    let announceTimer = 0;

    /**
     * A row that holds other rows is a container, not a field.
     *
     * Eight of the sheet's repeaters are wrapped in a labelled row of their own, so the wrapper
     * contains every control inside it. Left alone that would count one change twice, draw a score
     * down nine rows at once, and make "fields" mean something nobody could check. So a wrapper is
     * marked only as a container — it stays visible under the filter, it paints no line, and it is
     * never counted as a field.
     *
     * This is asked of the DOM on every pass rather than decided once at boot, because an empty
     * repeater has no rows inside it yet: the wrapper of the hero slides is a field until the
     * first slide is added, and a container from then on.
     *
     * @param {Element} field
     * @returns {boolean}
     */
    const isGroup = (field) => field.querySelector('.hb-field') !== null;

    /**
     * @param {Element} scope
     * @returns {Element[]} the rows that actually hold a value
     */
    const leafFields = (scope) => sheetList('.hb-field', scope).filter((field) => !isGroup(field));

    const repaint = () => {
        sheetList('.hb-block', root).forEach((block) => {
            const marks = countIn(block);
            const rows = leafFields(block).length;

            if (marks > 0) {
                block.setAttribute('data-marked', '');
            } else {
                block.removeAttribute('data-marked');
            }

            const blockCount = block.querySelector('[data-sheet-count]');

            if (blockCount) {
                /* A block that holds a list rather than a form has no fields, and "0 fields" is a
                   number that describes nothing. The rail already stays quiet about it. */
                blockCount.textContent = rows === 0 ? '' : `${rows} ${rows === 1 ? 'field' : 'fields'}`;
            }

            const blockMarks = block.querySelector('[data-sheet-marks]');

            if (blockMarks) {
                blockMarks.textContent = marks === 0 ? '' : `${marks} to set`;
            }
        });

        sheetList('.hb-field', root).forEach((field) => {
            const group = isGroup(field);

            if (group) {
                field.setAttribute('data-group', '');
            } else {
                field.removeAttribute('data-group');
            }

            // A group is marked because something inside it is, so that the filter
            // keeps it on screen; it never paints a line and never says "not set yet".
            const marked = group ? countIn(field) > 0 : fieldIsMarked(field);

            if (marked) {
                field.setAttribute('data-marked', '');
            } else {
                field.removeAttribute('data-marked');
            }

            // `:scope >` because a group's own state line is a direct child; without it the
            // first inner row's state would be written twice, once for the group.
            const state = field.querySelector(':scope > .hb-field__ctl > [data-sheet-state]');

            if (!state) {
                return;
            }

            /* The browser's refusal owns the state line while it stands. A repaint
               that wrote "Not set yet" over "That is not an email address…" would
               replace a sentence you can act on with one that is merely true. */
            if (field.hasAttribute('data-invalid')) {
                return;
            }

            if (group) {
                const n = countIn(field);

                state.textContent = n === 0 ? '' : `${n} ${n === 1 ? 'row' : 'rows'} not set`;
            } else {
                state.textContent = marked ? 'Not set yet' : '';
            }
        });

        /* A repeater row that has moved is a change, and it is the only change
           on the page that is not a value in a box. */
        sheetList('[data-sheet-list]', root).forEach((list) => {
            sheetList(':scope > [data-sheet-order]', list).forEach((row, index) => {
                if (!orders.has(row) || orders.get(row) !== index) {
                    row.setAttribute('data-marked', '');
                } else if (countIn(row) === 0) {
                    row.removeAttribute('data-marked');
                }
            });
        });

        sheetList('[data-sheet-pane]', root).forEach((pane) => {
            const item = root.querySelector(
                `[data-sheet-index="${pane.getAttribute('data-sheet-pane')}"]`,
            );

            if (!item) {
                return;
            }

            const marks = countIn(pane);
            const rows = leafFields(pane).length;

            if (marks > 0) {
                item.setAttribute('data-marked', '');
            } else {
                item.removeAttribute('data-marked');
            }

            const count = item.querySelector('[data-sheet-count]');

            if (count) {
                /* A zero is not a fact worth printing. A group with no rows in it —
                   the passkeys island, the sessions list — reads better with nothing
                   beside its name than with a column of zeroes. */
                count.textContent = rows === 0 ? '' : String(rows);
            }

            const tick = item.querySelector('[data-sheet-marks]');

            if (tick) {
                tick.textContent = marks > 0 ? String(marks) : '';
                tick.hidden = marks === 0;
            }
        });

        sheetList('[data-sheet-form]', root).forEach((form) => {
            const marks = countIn(form);
            const groups = groupsIn(form);
            const foot = form.querySelector('[data-sheet-foot]');
            const count = foot && foot.querySelector('[data-sheet-count]');
            const filter = foot && foot.querySelector('[data-sheet-filter]');

            if (count) {
                /* The payoff. A commit is a POST and a redirect, so the page that comes back has
                   nothing marked — and "Nothing marked" is not an answer to the question the
                   person just asked. The server says which of the two it is by putting
                   `data-just-set` on the foot when it flashes a save, and the claim is retired the
                   moment anything diverges again, so it cannot survive into a new edit. */
                const refused = sheetList('.hb-field[data-invalid]', form).length;

                if (refused > 0) {
                    count.textContent = `${refused} ${refused === 1 ? 'field needs' : 'fields need'} attention before this can be set.`;
                } else if (marks === 0 && foot.hasAttribute('data-just-set')) {
                    count.textContent = 'Set. What is on this sheet is what the site serves.';
                } else {
                    foot.removeAttribute('data-just-set');
                    count.textContent = sheetMarksSentence(marks, groups);
                }
            }

            if (foot) {
                if (marks > 0) {
                    foot.setAttribute('data-open', '');
                } else {
                    foot.removeAttribute('data-open');

                    /* The filter shows only what you changed. When there is
                       nothing left to show it releases itself, rather than
                       leaving a blank sheet and a button nobody can read. */
                    if (form.getAttribute('data-filter') === 'marks') {
                        form.removeAttribute('data-filter');

                        if (filter) {
                            filter.setAttribute('aria-pressed', 'false');
                            filter.querySelector('[data-sheet-filter-word]').textContent =
                                filter.getAttribute('data-off-word') || 'Show only marks';
                        }
                    }
                }
            }

            if (filter) {
                filter.disabled = marks === 0;
            }
        });

        const fields = root.querySelector('[data-sheet-fields]');

        if (fields) {
            fields.textContent = String(leafFields(root).length);
        }

        const open = root.querySelector('[data-sheet-total]');
        const live = root.querySelector('[data-sheet-live]');

        if (open) {
            const marks = countIn(root);

            open.textContent = String(marks);

            /* One sentence for the whole page, and only when the total moves —
               a screen reader would otherwise hear a mark on every keystroke. */
            if (live && live.dataset.last !== String(marks)) {
                window.clearTimeout(announceTimer);
                announceTimer = window.setTimeout(() => {
                    live.dataset.last = String(marks);
                    live.textContent = sheetMarksSentence(marks, groupsIn(root));
                }, 1200);
            }
        }
    };

    const schedule = () => {
        if (frame) {
            return;
        }

        frame = window.requestAnimationFrame(() => {
            frame = 0;
            repaint();
        });
    };

    /* Every listener this module adds is recorded here and taken away again on
       teardown. That matters more than it looks: Livewire fires
       `livewire:navigated` once after an ordinary page load as well, so a module
       that boots twice and does not unbind leaves two click handlers on the same
       button — which is a filter that turns itself on and straight back off in
       the same click, and looks exactly like a dead control. */
    const bound = [];

    const on = (node, type, handler, capture) => {
        node.addEventListener(type, handler, capture);
        bound.push([node, type, handler, capture]);
    };

    /* Delegated, and deliberately including `click`: Alpine writes some of these
       values from a button (remove the logo, pick the image from the library,
       add a slide) and a programmatic assignment fires no input event at all. */
    ['input', 'change', 'click', 'keydown'].forEach((type) => on(root, type, schedule, true));

    /**
     * The browser's own constraint check, in this sheet's words.
     *
     * `invalid` does not bubble, so it is caught on the capture phase at the root.
     * Cancelling it only removes the bubble: the submit is still blocked by the
     * constraint, which is the part worth keeping.
     */
    const onInvalid = (event) => {
        const control = event.target;

        if (!control || !control.closest) {
            return;
        }

        const field = control.closest('.hb-field');

        if (!field) {
            return;
        }

        event.preventDefault();

        field.setAttribute('data-invalid', '');
        control.setAttribute('aria-invalid', 'true');

        const state = field.querySelector(':scope > .hb-field__ctl > [data-sheet-state]');

        if (state) {
            state.textContent = sheetInvalidText(control);
        }

        schedule();

        // The browser would have moved the caret there itself had the bubble been
        // allowed. Without that, a submit from the bottom of a long sheet would do
        // nothing at all and say nothing about where the nothing happened.
        if (document.activeElement !== control) {
            control.focus({ preventScroll: false });
        }
    };

    on(root, 'invalid', onInvalid, true);

    /**
     * A row stops being wrong as soon as it is edited. The browser re-runs the
     * check on the next submit, so nothing is hidden here that would not surface.
     */
    const clearInvalid = (control) => {
        const field = control.closest ? control.closest('.hb-field') : null;

        if (field && field.hasAttribute('data-invalid')) {
            field.removeAttribute('data-invalid');
            control.removeAttribute('aria-invalid');
        }
    };

    const onEdit = (event) => {
        if (event.target && event.target.type !== undefined) {
            clearInvalid(event.target);
        }
    };

    on(root, 'input', onEdit, true);

    sheetList('[data-sheet-filter]', root).forEach((button) => {
        const onWord = button.getAttribute('data-on-word') || 'Showing marks only';
        const offWord = button.getAttribute('data-off-word') || 'Show only marks';
        const form = button.closest('[data-sheet-form]');

        const toggle = () => {
            const active = form && form.getAttribute('data-filter') === 'marks';

            if (active) {
                form.removeAttribute('data-filter');
                button.setAttribute('aria-pressed', 'false');
            } else {
                if (form) {
                    form.setAttribute('data-filter', 'marks');
                }

                button.setAttribute('aria-pressed', 'true');
            }

            const word = button.querySelector('[data-sheet-filter-word]');

            if (word) {
                word.textContent = active ? offWord : onWord;
            }

            schedule();
        };

        on(button, 'click', toggle);
    });

    /* The one timer. It exists because the shop's settings were last written to
       at a real moment, and the page is honest about which one. */
    let clock = 0;

    const readouts = sheetList('[data-sheet-elapsed]', root);

    const tickClock = () => {
        readouts.forEach((node) => {
            const at = new Date(node.getAttribute('data-at') || '');

            node.textContent = sheetElapsedText(at, new Date());
            node.setAttribute('title', node.getAttribute('data-at') || '');
        });

        clock = window.setTimeout(tickClock, 60000 - (Date.now() % 60000) + 400);
    };

    const onVisibility = () => {
        window.clearTimeout(clock);
        clock = 0;

        if (!document.hidden) {
            tickClock();
        }
    };

    if (readouts.length > 0) {
        tickClock();
        on(document, 'visibilitychange', onVisibility);
    }

    /* A Livewire pane can arrive after the boot pass (the passkeys island), so
       one re-read on a navigation is worth the cost of re-seeding. */
    const onNavigated = () => window.setTimeout(schedule, 0);

    on(document, 'livewire:navigated', onNavigated);

    schedule();

    return () => {
        if (frame) {
            window.cancelAnimationFrame(frame);
        }

        window.clearTimeout(clock);
        window.clearTimeout(announceTimer);

        bound.forEach(([node, type, handler, capture]) => {
            node.removeEventListener(type, handler, capture);
        });

        bound.length = 0;
        root.removeAttribute('data-filter');
    };
}

let sheetTeardown = null;

function sheetRefresh() {
    if (typeof sheetTeardown === 'function') {
        sheetTeardown();
        sheetTeardown = null;
    }

    sheetTeardown = bootSheet(document.querySelector('.hb-sheet'));
}

document.addEventListener('DOMContentLoaded', sheetRefresh);
document.addEventListener('livewire:navigated', () => window.setTimeout(sheetRefresh, 0));
