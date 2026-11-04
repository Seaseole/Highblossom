import collapse from '@alpinejs/collapse';
import { Passkeys } from '@laravel/passkeys';
import './password-rules';
import './admin-sheet';

console.log('Admin JS: Script loaded');

// Initialize Passkeys
window.Passkeys = Passkeys;

/**
 * Keep Tab inside `container`.
 *
 * Written by hand because @alpinejs/focus is not installed in this bundle, so x-trap would fail
 * silently and Tab would walk out from under the person using the overlay. Elements hidden by a
 * collapsed group are filtered out, otherwise the first/last pair brackets invisible links.
 *
 * The container is a parameter rather than `this.$el` so that every overlay on the desk cycles
 * through this one function: the drawer and the command palette call it through `trapTab`, and
 * anything wearing `x-dialog` calls it directly. Two copies is how two overlays start disagreeing
 * about what counts as focusable.
 *
 * @param {Element} container
 * @param {KeyboardEvent} event
 */
function trapWithin(container, event) {
    if (event.key !== 'Tab' || !container) {
        return;
    }

    const focusables = Array.from(
        container.querySelectorAll(
            'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
        ),
    ).filter((element) => element.offsetParent !== null);

    if (focusables.length === 0) {
        return;
    }

    const first = focusables[0];
    const last = focusables[focusables.length - 1];

    if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
    } else if (! event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
    }
}

/**
 * The drawer's and palette's view of the cycle, kept as it was so their wiring is untouched.
 *
 * @param {KeyboardEvent} event
 */
function trapTab(event) {
    trapWithin(this.$el, event);
}

/**
 * Whether a caller that manages the lock itself — the create dialog, the taxonomy drawer, the
 * palette — is currently holding it.
 */
let legacyScrollLock = false;

/**
 * Put the desk's scroller to sleep while anything is covering it.
 *
 * Two owners, one rule. `lockAdminContentScroll()` is the old, idempotent switch those three
 * surfaces already call, and the stack below is what anything wearing `x-dialog` goes through. As
 * a single boolean the second overlay to close switched the scroller back on while the first was
 * still covering the page; as a counter it could drift upward if a surface re-opened without
 * closing, which strands the desk unable to scroll for the rest of the visit. Reading both owners
 * on every change can do neither.
 */
function applyScrollLock() {
    document.querySelector('.admin-main')?.classList.toggle(
        'is-scroll-locked',
        legacyScrollLock || openDialogs.length > 0,
    );
}

/**
 * Lock or release the desk's scroller for one of the surfaces that owns the flag itself.
 *
 * @param {boolean} locked
 */
function lockAdminContentScroll(locked) {
    legacyScrollLock = Boolean(locked);
    applyScrollLock();
}

/**
 * Overlays currently standing, innermost last.
 *
 * Keyboard handling is offered to the top of this stack and to nothing else. Without it, Escape
 * pressed over a confirm that was asked from inside a dialog would close the dialog behind it, and
 * Tab would cycle through whichever panel registered its listener first.
 */
const openDialogs = [];

/**
 * @param {Element} el
 */
function pushDialog(el) {
    openDialogs.push(el);
    applyScrollLock();
}

/**
 * @param {Element} el
 */
function popDialog(el) {
    const index = openDialogs.lastIndexOf(el);

    if (index === -1) {
        return;
    }

    openDialogs.splice(index, 1);
    applyScrollLock();
}

/**
 * @returns {Element|null}
 */
function topDialog() {
    return openDialogs.length > 0 ? openDialogs[openDialogs.length - 1] : null;
}

/**
 * The first thing worth standing on inside an overlay: whatever the author marked, and failing
 * that the first control that can take focus.
 *
 * @param {Element} container
 * @returns {HTMLElement|null}
 */
function firstDialogTarget(container) {
    const marked = container.querySelector('[data-dialog-initial]');

    /* A marked target is the author's answer and is taken even while it cannot be measured yet;
       see `focusWhenShown`, which only calls this once the panel is on screen. */
    if (marked) {
        return /** @type {HTMLElement} */ (marked);
    }

    return Array.from(
        container.querySelectorAll(
            'input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), a[href], [tabindex]:not([tabindex="-1"])',
        ),
    ).find((element) => element.offsetParent !== null) ?? null;
}

/**
 * Put the caret on the first thing in the panel, once the panel can hold it.
 *
 * A frame, not a timeout: `x-show` without a transition reveals on `setTimeout`, but with one it
 * reveals inside `requestAnimationFrame` (Alpine's `performTransition`), so a focus attempt made on
 * the same tick finds an element that is still `display: none` and measures nothing. This retries
 * down to the first frame where the panel is actually on screen and stops after eight, because an
 * overlay that never became visible is a bug in the page, not something to keep polling.
 *
 * @param {Element} container
 * @param {() => boolean} stillOpen
 * @param {number} attempt
 */
function focusWhenShown(container, stillOpen, attempt = 0) {
    window.requestAnimationFrame(() => {
        if (! stillOpen() || ! document.contains(container)) {
            return;
        }

        const target = firstDialogTarget(container);

        if (target) {
            target.focus();

            return;
        }

        if (attempt < 8) {
            focusWhenShown(container, stillOpen, attempt + 1);
        }
    });
}

/**
 * The key the command palette keeps its own history under, and the read of it.
 *
 * A store is not an Alpine component, so it gets no init() hook; the history therefore has to be
 * in place when the store object is written rather than filled in afterwards.
 */
const PALETTE_RECENT_KEY = 'adminPaletteRecent';

function readPaletteRecents() {
    try {
        const stored = JSON.parse(window.localStorage.getItem(PALETTE_RECENT_KEY) || '[]');

        return Array.isArray(stored) ? stored.filter((term) => typeof term === 'string').slice(0, 6) : [];
    } catch (cause) {
        // Safari in private mode throws on the read; an empty history is not worth a fault.
        return [];
    }
}

// Register plugins and components when Alpine is ready
document.addEventListener('alpine:init', () => {
    console.log('Admin JS: alpine:init fired');

    // Register plugins
    window.Alpine.plugin(collapse);

    /* The one focus cycle, named for what it does rather than for the element that first used
       it: the mobile drawer and the command palette's dialog both have to keep Tab inside, and
       a second copy is how the two start disagreeing about what counts as focusable. */
    window.Alpine.data('adminFocusTrap', () => ({
        trap: trapTab,
    }));

    /* An overlay's obligations, in one place.
     *
     * A profile modal used to be a `x-show` and a wish: Tab walked out from behind it, Escape
     * worked only when focus happened to already be inside it, focus went to <body> when it shut,
     * and the desk scrolled under it. Each of those was written once per modal, which is how three
     * of them ended up disagreeing.
     *
     * `x-dialog="flag"` on the overlay's panel takes all of it off the author. The flag is read
     * through the element's own scope chain, so a modal can sit inside a page-wide `x-data` and
     * still watch one of its properties. Semantics (`role="dialog"`, `aria-modal`,
     * `aria-labelledby`) stay in the markup, where a screen reader needs them with the JavaScript
     * off.
     *
     * The key handler sits on the document and defers to the top of the dialog stack, not because
     * a handler on the panel reads better but because it does not work: while `x-show` is still
     * waiting on a frame to reveal the panel, focus is not inside it yet, so a panel-bound listener
     * hears nothing and the first Escape a person presses is lost. */
    window.Alpine.directive('dialog', (el, { expression }, { effect, evaluate, evaluateLater, cleanup }) => {
        const read = evaluateLater(expression);
        /* Escape shuts the overlay. Most authors just want the flag put back, and that is the
           default; a dialog whose state lives in a store names its own way out instead, because
           dropping `open` without settling the pending question would leave the caller waiting on
           a promise nobody can answer. */
        const close = () => (el.dataset.dialogClose
            ? evaluate(el.dataset.dialogClose)
            : evaluate(`${expression} = false`));

        let wasOpen = false;
        let returnTo = null;

        const onKeyDown = (event) => {
            if (topDialog() !== el) {
                return;
            }

            if (event.key === 'Escape') {
                event.stopPropagation();
                close();

                return;
            }

            if (event.key !== 'Tab') {
                return;
            }

            /* Tab pulls focus back in rather than letting it walk past the overlay. A panel that
               has just been opened has not been focused yet — and with a transition it may not have
               been focusable a frame ago — so this is the common case, not the corner. */
            if (! el.contains(document.activeElement)) {
                event.preventDefault();
                firstDialogTarget(el)?.focus();

                return;
            }

            trapWithin(el, event);
        };

        const onToggle = (open) => {
            if (open && !wasOpen) {
                wasOpen = true;
                returnTo = document.activeElement;
                pushDialog(el);
                document.addEventListener('keydown', onKeyDown, true);
                focusWhenShown(el, () => wasOpen);

                return;
            }

            if (! open && wasOpen) {
                wasOpen = false;
                document.removeEventListener('keydown', onKeyDown, true);
                popDialog(el);

                /* Back where the person was standing, provided that spot still exists. It does
                   not after a save redraws the row that opened the dialog. */
                if (returnTo && document.contains(returnTo)) {
                    returnTo.focus();
                }

                returnTo = null;
            }
        };

        effect(() => read(onToggle));

        cleanup(() => {
            document.removeEventListener('keydown', onKeyDown, true);

            if (wasOpen) {
                wasOpen = false;
                popDialog(el);
            }
        });
    });

    /* One ask, in this design's words.
     *
     * Three tabs, three confirm idioms: a browser confirm() written inline in an onsubmit, another
     * inside Alpine, and Livewire's wire:confirm, which the bundle resolves to window.confirm as
     * well. All three are blocking, none of them read as this product, and on a phone they are a
     * system sheet over a form the person was mid-way through.
     *
     * `adminConfirm({...})` answers the same question in the desk's own panel and resolves to a
     * boolean. Forms use it without any JavaScript of their own by carrying `data-confirm`, and
     * Livewire islands can drop wire:confirm for the same helper, so one screen asks one way. */
    Alpine.store('adminConfirm', {
        open: false,
        title: '',
        message: '',
        confirmLabel: '',
        cancelLabel: '',
        danger: false,
        answer: null,

        /**
         * @param {{title: string, message: string, confirm?: string, cancel?: string, danger?: boolean}} ask
         * @param {(proceed: boolean) => void} resolve
         */
        ask(ask, resolve) {
            this.title = ask.title;
            this.message = ask.message;
            this.confirmLabel = ask.confirm || 'Continue';
            this.cancelLabel = ask.cancel || 'Cancel';
            this.danger = Boolean(ask.danger);
            this.answer = resolve;
            this.open = true;
        },

        /**
         * @param {boolean} proceed
         */
        settle(proceed) {
            if (! this.open) {
                return;
            }

            const answer = this.answer;

            this.open = false;
            this.answer = null;

            if (answer) {
                answer(Boolean(proceed));
            }
        },
    });

    /**
     * Ask the person before an action runs.
     *
     * @param {{title: string, message: string, confirm?: string, cancel?: string, danger?: boolean}} ask
     * @returns {Promise<boolean>}
     */
    window.adminConfirm = (ask) => new Promise((resolve) => {
        const store = Alpine.store('adminConfirm');

        /* A second ask over an unanswered one would strand the first promise. The newest question
           is the one on screen, so the older one resolves as a refusal rather than hanging. */
        store.settle(false);
        store.ask(ask, resolve);
    });

    /* A question answered against a page that has already gone is not answerable.
     *
     * The store outlives the panel: `adminConfirm` lives on the module, while the dialog asking it
     * is re-rendered by every navigation. Without this, a person who navigated away from "Remove
     * this passkey" while it was still open would arrive somewhere else with the same question on
     * screen, and pressing Yes would call `$wire.deletePasskey()` on a component that no longer
     * exists. Settling as a refusal also releases the caller's promise, which nothing else can do
     * once its island is gone. */
    document.addEventListener('livewire:navigated', () => {
        Alpine.store('adminConfirm').settle(false);
    });

    /* The declarative half: a form that has to be confirmed before it may submit.
     *
     * Held on the capture phase so it runs before the browser starts the navigation, and marked
     * on the element rather than in a WeakMap because a re-render can hand us a new node for the
     * same form. */
    document.addEventListener(
        'submit',
        (event) => {
            const form = event.target;

            if (! form || ! form.matches || ! form.matches('form[data-confirm]') || form.dataset.confirmed === '1') {
                return;
            }

            event.preventDefault();

            const proceed = async () => {
                const yes = await window.adminConfirm({
                    title: form.dataset.confirmTitle || 'Confirm this',
                    message: form.dataset.confirm,
                    confirm: form.dataset.confirmYes || 'Continue',
                    danger: form.dataset.confirmDanger === '1',
                });

                if (! yes) {
                    return;
                }

                form.dataset.confirmed = '1';

                /* requestSubmit() would run the validation the browser already ran and then be
                   intercepted by this same listener; the flag stops the loop either way. */
                form.submit();
            };

            proceed();
        },
        true,
    );

    // Types whose editors host nested block lists; they cannot be added inside a nested list.
    const NESTING_TYPES = ['columns', 'tabs', 'carousel'];

    // Mirrors Highblossom\ContentBlocks\Blocks\ImageBlock::MAX_IMAGES.
    const IMAGE_SET_MAX = 24;

    // Media library picker used by <x-media-picker /> in the admin layout.
    window.Alpine.data('mediaPicker', () => ({
        field: null,
        multiple: false,
        max: IMAGE_SET_MAX,
        isOpen: false,
        images: [],
        loading: false,
        searchTerm: '',
        page: 1,
        lastPage: 1,
        hasMore: false,
        total: 0,
        selectedImage: null,
        selected: [],

        init() {
            this.$watch('searchTerm', () => {
                this.page = 1;
                this.loadImages();
            });
        },

        open(detail) {
            this.field = detail.field || null;
            this.multiple = Boolean(detail.multiple);
            this.max = detail.max || IMAGE_SET_MAX;
            this.isOpen = true;
            this.searchTerm = '';
            this.page = 1;
            this.selectedImage = null;
            this.selected = [];

            this.loadImages();
        },

        async loadImages(append = false) {
            this.loading = true;
            try {
                const params = new URLSearchParams({ page: String(this.page), per_page: '24' });

                if (this.searchTerm.trim()) params.set('search', this.searchTerm.trim());

                const response = await fetch('/admin/media-library?' + params.toString(), {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await response.json();
                const incoming = (data.images || []).filter((image) => image.url);

                this.images = append ? [...this.images, ...incoming] : incoming;
                this.page = data.meta?.current_page || this.page;
                this.lastPage = data.meta?.last_page || 1;
                this.hasMore = Boolean(data.meta?.has_more);
                this.total = data.meta?.total ?? this.images.length;
            } catch (error) {
                console.error('Failed to load images:', error);
            } finally {
                this.loading = false;
            }
        },

        loadMore() {
            if (!this.hasMore || this.loading) return;

            this.page += 1;
            this.loadImages(true);
        },

        async uploadImage(event) {
            const files = Array.from(event.target.files || []);
            if (!files.length) return;

            this.loading = true;
            try {
                for (const file of files) {
                    const formData = new FormData();
                    formData.append('upload', file);
                    formData.append('title', file.name.replace(/\.[^.]+$/, ''));
                    formData.append('category', 'other');

                    const response = await fetch('/admin/media-library/upload', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) continue;

                    const created = await response.json();

                    if (!created?.url) continue;

                    if (this.multiple) {
                        this.addSelected({ id: created.id, url: created.url, alt: file.name });
                    } else {
                        this.selectedImage = { id: created.id, url: created.url, path: created.path, name: file.name };
                    }
                }

                await this.loadImages();
            } catch (error) {
                console.error('Failed to upload image:', error);
            } finally {
                this.loading = false;
                event.target.value = '';
            }
        },

        isSelected(image) {
            return this.selected.some((item) => item.id === image.id);
        },

        addSelected(image) {
            if (this.isSelected(image) || this.selected.length >= this.max) return;

            this.selected.push({ id: image.id, url: image.url, path: image.path, alt: image.alt || image.name || '' });
        },

        toggle(image) {
            if (!this.multiple) {
                this.selectedImage = this.selectedImage?.id === image.id ? null : image;

                return;
            }

            if (this.isSelected(image)) {
                this.selected = this.selected.filter((item) => item.id !== image.id);

                return;
            }

            this.addSelected(image);
        },

        orderOf(image) {
            return this.selected.findIndex((item) => item.id === image.id) + 1;
        },

        clearSelection() {
            this.selected = [];
            this.selectedImage = null;
        },

        confirmSelection() {
            if (!this.field) {
                this.isOpen = false;

                return;
            }

            if (this.multiple) {
                if (this.selected.length) {
                    this.$dispatch('images-selected', { field: this.field, items: [...this.selected] });
                }
            } else if (this.selectedImage) {
                this.$dispatch('image-selected', {
                    field: this.field,
                    url: this.selectedImage.url,
                    path: this.selectedImage.path,
                });
            }

            this.isOpen = false;
            this.selectedImage = null;
            this.selected = [];
        },
    }));

    // Character counter wired to a single field; the field keeps its native value
    // as the source of truth so server-side old() repopulation still works.
    window.Alpine.data('charCount', (max) => ({
        max: max,
        count: 0,

        init() {
            const field = this.$el.querySelector('[name]');

            this.count = field ? field.value.length : 0;
        },

        nearLimit() {
            return this.count > this.max * 0.9;
        },
    }));

    // Publish gate for the post editor. Every rule is re-checked server-side by
    // StoreBlockContentRequest; this only stops an obviously doomed submit, so all
    // state is read back from the form rather than tracked separately.
    window.Alpine.data('postEditor', () => ({
        status: 'draft',
        excerptLength: 0,
        categoryCount: 0,
        tagCount: 0,
        blockCount: 0,
        blocked: [],
        forceDraft: false,

        init() {
            this.refreshCounts();

            window.addEventListener('blocks-changed', () => this.refreshCounts());
        },

        refreshCounts() {
            const form = this.$el;
            const excerpt = form.querySelector('[name="excerpt"]');
            const content = form.querySelector('#content-input');
            const status = form.querySelector('[name="status"]');

            this.excerptLength = excerpt ? excerpt.value.trim().length : 0;
            this.categoryCount = form.querySelectorAll('input[name="categories[]"]:checked').length;
            this.tagCount = form.querySelectorAll('input[name="tags[]"]:checked').length;
            this.status = status ? status.value : 'draft';

            try {
                this.blockCount = JSON.parse(content ? content.value : '[]').length;
            } catch (error) {
                this.blockCount = 0;
            }
        },

        missing() {
            const missing = [];

            if (! this.blockCount) missing.push('at least one content block');
            if (! this.excerptLength) missing.push('an excerpt');
            if (! this.categoryCount) missing.push('a category');

            return missing;
        },

        guardSubmit(event) {
            this.refreshCounts();

            if (this.forceDraft || this.status !== 'published') return;

            const missing = this.missing();

            if (missing.length) {
                event.preventDefault();
                this.blocked = missing;
            }
        },

        saveAsDraft() {
            const status = this.$el.querySelector('[name="status"]');

            if (status) status.value = 'draft';

            this.status = 'draft';
            this.blocked = [];
            this.forceDraft = true;
            this.$el.requestSubmit();
        },
    }));

    // Quick-add modal for the post editor's category and tag chips. The created option
    // is cloned from an inert <template> and ticked, so the post form keeps its unsaved
    // state and the page never navigates.
    window.Alpine.data('taxonomy', (routes) => ({
        kind: null,
        name: '',
        errors: [],
        saving: false,
        routes: routes,

        get isOpen() {
            return this.kind !== null;
        },

        get title() {
            return this.kind === 'category' ? 'New category' : 'New tag';
        },

        open(kind) {
            this.kind = kind;
            this.name = '';
            this.errors = [];

            this.$nextTick(() => {
                lockAdminContentScroll(true);

                if (this.$refs.input) this.$refs.input.focus();
            });
        },

        close() {
            if (! this.isOpen) return;

            this.kind = null;
            this.errors = [];
            lockAdminContentScroll(false);
        },

        // @alpinejs/focus is not installed, so x-trap is unavailable and the cycle is
        // written by hand or focus would escape behind the overlay.
        trap(event) {
            if (event.key !== 'Tab' || ! this.$refs.dialog) return;

            const focusables = Array.from(this.$refs.dialog.querySelectorAll('input, button'))
                .filter((el) => ! el.disabled);

            if (! focusables.length) return;

            const first = focusables[0];
            const last = focusables[focusables.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (! event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },

        async save() {
            if (this.saving || ! this.isOpen) return;

            this.saving = true;
            this.errors = [];

            try {
                const response = await fetch(this.routes[this.kind], {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    },
                    body: JSON.stringify({ name: this.name }),
                });

                const data = await response.json().catch(() => ({}));

                if (! response.ok) {
                    this.errors = Object.values(data.errors ?? {}).flat();

                    if (! this.errors.length) {
                        this.errors = [data.message || 'Could not save.'];
                    }

                    return;
                }

                const kind = this.kind;

                this.addChip(kind, data);
                this.close();

                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { type: 'success', message: data.name + ' added and selected.' },
                }));
            } catch (error) {
                this.errors = ['Network error — nothing was saved.'];
            } finally {
                this.saving = false;
            }
        },

        addChip(kind, created) {
            const list = document.getElementById(kind + '-chip-list');
            const template = document.getElementById(kind + '-chip-template');

            if (! list || ! template) return;

            const chip = template.content.firstElementChild.cloneNode(true);
            const input = chip.querySelector('input');

            input.name = kind === 'category' ? 'categories[]' : 'tags[]';
            input.value = String(created.id);
            input.checked = true;
            chip.querySelector('span').textContent = kind === 'category' ? created.name : '#' + created.name;

            document.getElementById(kind + '-chip-empty')?.remove();
            list.append(chip);

            // Bubbles to the form's change listener, which refreshes the publish checklist.
            input.dispatchEvent(new Event('change', { bubbles: true }));
        },
    }));

    // Sub-categories on the Edit Glass Type page. One modal covers both create and edit:
    // rows post JSON to the same resource routes the standalone pages use, and the affected
    // row is cloned or repainted in place so the surrounding glass-type form keeps its
    // unsaved values instead of being discarded by a navigation.
    window.Alpine.data('subCategories', (base, glassTypeId, initialCount, nextSortOrder) => ({
        base: base,
        glassTypeId: glassTypeId,
        count: initialCount,
        nextSortOrder: Number(nextSortOrder) || 1,
        mode: null,
        id: null,
        row: null,
        name: '',
        slug: '',
        slugEdited: false,
        sortOrder: Number(nextSortOrder) || 1,
        isActive: true,
        errors: [],
        saving: false,

        get isOpen() {
            return this.mode !== null;
        },

        get isCreate() {
            return this.mode === 'create';
        },

        get title() {
            return this.isCreate ? 'New sub-category' : 'Edit sub-category';
        },

        openCreate() {
            this.mode = 'create';
            this.id = null;
            this.row = null;
            this.name = '';
            this.slug = '';
            this.slugEdited = false;
            this.sortOrder = this.nextSortOrder;
            this.isActive = true;
            this.errors = [];

            this.focusDialog();
        },

        openEdit(row) {
            this.mode = 'edit';
            this.id = Number(row.dataset.id);
            this.row = row;
            this.name = row.dataset.name;
            this.slug = row.dataset.slug ?? '';
            this.slugEdited = true;
            const stored = Number(row.dataset.sortOrder);
            this.sortOrder = Number.isFinite(stored) && stored > 0 ? stored : '';
            this.isActive = row.dataset.active !== '0';
            this.errors = [];

            this.focusDialog();
        },

        focusDialog() {
            this.$nextTick(() => {
                lockAdminContentScroll(true);

                if (this.$refs.input) this.$refs.input.focus();
            });
        },

        close() {
            if (! this.isOpen) return;

            this.mode = null;
            this.row = null;
            this.errors = [];
            lockAdminContentScroll(false);
        },

        // @alpinejs/focus is not installed, so x-trap is unavailable and the cycle is
        // written by hand or focus would escape behind the overlay.
        trap(event) {
            if (event.key !== 'Tab' || ! this.$refs.dialog) return;

            const focusables = Array.from(this.$refs.dialog.querySelectorAll('input, button'))
                .filter((el) => ! el.disabled);

            if (! focusables.length) return;

            const first = focusables[0];
            const last = focusables[focusables.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (! event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },

        autoSlug() {
            if (this.slugEdited) return;

            this.slug = this.name
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        },

        async save() {
            if (this.saving || ! this.isOpen) return;

            this.saving = true;
            this.errors = [];

            const creating = this.isCreate;
            const url = creating ? this.base : this.base + '/' + this.id;

            try {
                const response = await fetch(url, {
                    method: creating ? 'POST' : 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    },
                    body: JSON.stringify({
                        glass_type_id: this.glassTypeId,
                        name: this.name,
                        slug: this.slug,
                        sort_order: Number(this.sortOrder) > 0 ? Number(this.sortOrder) : null,
                        is_active: this.isActive,
                    }),
                });

                const data = await response.json().catch(() => ({}));

                if (! response.ok) {
                    this.errors = Object.values(data.errors ?? {}).flat();

                    if (! this.errors.length) {
                        this.errors = [data.message || 'Could not save.'];
                    }

                    return;
                }

                if (creating) {
                    this.appendRow(data);
                } else {
                    this.paintRow(this.row, data);
                }

                this.close();

                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { type: 'success', message: data.name + (creating ? ' created.' : ' updated.') },
                }));
            } catch (error) {
                this.errors = ['Network error — nothing was saved.'];
            } finally {
                this.saving = false;
            }
        },

        appendRow(created) {
            const list = document.getElementById('sub-category-list');
            const template = document.getElementById('sub-category-row-template');

            if (! list || ! template) return;

            const row = template.content.firstElementChild.cloneNode(true);

            this.paintRow(row, created);
            list.append(row);
            this.count += 1;
            this.nextSortOrder = Math.max(this.nextSortOrder, Number(created.sort_order) + 1);
        },

        paintRow(row, data) {
            row.dataset.id = String(data.id);
            row.dataset.name = data.name;
            row.dataset.slug = data.slug;
            row.dataset.sortOrder = String(data.sort_order);
            row.dataset.active = data.is_active ? '1' : '0';

            row.querySelector('[data-row-name]').textContent = data.name;
            row.querySelector('[data-row-slug]').textContent = data.slug;
            row.querySelector('[data-row-inactive]').classList.toggle('hidden', Boolean(data.is_active));
        },

        async remove(row) {
            if (! window.confirm('Delete this sub-category? This cannot be undone.')) return;

            try {
                const response = await fetch(this.base + '/' + row.dataset.id, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    },
                });

                const data = await response.json().catch(() => ({}));

                if (! response.ok) {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { type: 'error', message: data.message || 'Could not delete.' },
                    }));

                    return;
                }

                row.remove();
                this.count -= 1;

                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { type: 'success', message: 'Sub-category deleted.' },
                }));
            } catch (error) {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { type: 'error', message: 'Network error — nothing was deleted.' },
                }));
            }
        },
    }));

    // Status control for the posts index: a menu opened by clicking the status pill
    // or right-clicking a row, plus a multi-select bulk bar. Both paths post JSON and
    // repaint the affected pills in place rather than reloading the table.
    window.Alpine.data('postsTable', (bulkUrl) => ({
        bulkUrl: bulkUrl,
        statuses: {},
        selected: [],
        menu: { open: false, x: 0, y: 0, id: null, url: '' },
        busy: false,

        init() {
            this.rows().forEach((row) => {
                this.statuses[row.dataset.postId] = row.dataset.status;
            });

            document.addEventListener('click', (event) => {
                if (! event.target.closest('[data-status-menu], [data-status-trigger]')) {
                    this.menu.open = false;
                }
            });

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') this.menu.open = false;
            });
        },

        rows() {
            return Array.from(this.$el.querySelectorAll('[data-post-row]'));
        },

        label(postId) {
            return {
                draft: 'Draft',
                published: 'Published',
                scheduled: 'Scheduled',
                archived: 'Archived',
            }[this.statuses[postId]] || '';
        },

        pillClass(postId) {
            const status = this.statuses[postId];

            if (status === 'published') return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
            if (status === 'scheduled') return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
            if (status === 'archived') return 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';

            return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400';
        },

        // The menu only ever offers the states the row is not already in.
        offers(status) {
            const current = this.statuses[this.menu.id];

            if (current === undefined || current === status) return false;

            // A scheduled post is already published, so publishing it again is a no-op.
            return ! (status === 'published' && current === 'scheduled');
        },

        toggleAll(event) {
            // Checkbox values arrive as strings, so select-all must too or the
            // x-model comparison leaves every box visually unchecked.
            this.selected = event.target.checked
                ? this.rows().map((row) => row.dataset.postId)
                : [];
        },

        get allSelected() {
            return this.selected.length > 0 && this.selected.length === this.rows().length;
        },

        openMenu(event, postId, url) {
            event.preventDefault();
            event.stopPropagation();

            this.menu = {
                open: true,
                x: Math.min(event.clientX, window.innerWidth - 200),
                y: Math.min(event.clientY, window.innerHeight - 180),
                id: postId,
                url: url,
            };
        },

        async setStatus(status) {
            const postId = this.menu.id;

            this.menu.open = false;

            if (postId === null) return;

            const result = await this.send(this.menu.url, { status });

            if (result) this.applyStatuses(result.statuses);
        },

        async bulkSetStatus(status) {
            const ids = [...this.selected];

            if (! ids.length) return;

            const result = await this.send(this.bulkUrl, { status, posts: ids });

            if (! result) return;

            this.applyStatuses(result.statuses);
            this.selected = [];
        },

        applyStatuses(map) {
            Object.entries(map || {}).forEach(([postId, key]) => {
                this.statuses[postId] = key;
            });
        },

        async send(url, payload) {
            if (this.busy) return null;

            this.busy = true;

            try {
                const response = await fetch(url, {
                    method: url === this.bulkUrl ? 'PUT' : 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json().catch(() => ({}));

                this.toast(response.ok ? 'success' : 'error', data.message || 'Could not update the post status.');

                return response.ok ? data : null;
            } catch (error) {
                this.toast('error', 'Network error — the status was not changed.');

                return null;
            } finally {
                this.busy = false;
            }
        },

        toast(type, message) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { type, message } }));
        },
    }));

    window.Alpine.data('blockBuilder', (config) => ({
        blocks: Array.isArray(config.initialBlocks) ? config.initialBlocks : [],
        blockMeta: config.blockMeta || {},
        blockErrors: config.blockErrors || {},
        fieldName: config.name || 'content',
        mediaBase: config.mediaBase || '',
        collapsed: [],
        lastRemoved: null,
        dragIndex: null,
        dropIndex: null,
        previewOpen: false,
        previewHtml: '',
        previewLoading: false,

        // Block attributes store disk-relative media paths, so editor previews
        // must be resolved against whichever base URL the public disk uses.
        mediaSrc(value) {
            if (! value) {
                return '';
            }

            if (/^(https?:|temp:|data:|blob:)/i.test(value)) {
                return value;
            }

            let path = String(value).replace(/^\/+/, '');

            if (path.startsWith('storage/')) {
                path = path.slice('storage/'.length);
            }

            if (path === 'placeholder.gif') {
                return '/' + path;
            }

            return this.mediaBase + '/' + path;
        },

        init() {
            this.blocks.forEach((block) => this.prepareBlock(block));

            this.$watch('blocks', () => this.sync(), { deep: true });
            this.sync();

            window.addEventListener('image-uploaded', (e) => {
                const detail = e.detail[0] || e.detail;
                const block = this.findBlockById(detail.id);

                if (block) this.setAttributePath(block, detail.attribute || 'src', detail.url);
            });

            window.addEventListener('video-uploaded', (e) => {
                const detail = e.detail[0] || e.detail;
                const block = this.findBlockById(detail.id);

                if (!block) return;

                this.setAttributePath(block, 'src', detail.url);

                if (detail.poster) this.setAttributePath(block, 'poster', detail.poster);
            });

            window.addEventListener('image-selected', (e) => {
                const detail = e.detail[0] || e.detail;
                if (!detail || !detail.field || detail.field.indexOf(':') === -1) return;

                const sep = detail.field.indexOf(':');
                const block = this.findBlockById(detail.field.slice(0, sep));

                if (block) this.setAttributePath(block, detail.field.slice(sep + 1), detail.path || detail.url);
            });

            window.addEventListener('images-selected', (e) => {
                const detail = e.detail[0] || e.detail;
                if (!detail || !detail.field || detail.field.indexOf(':') === -1) return;

                const sep = detail.field.indexOf(':');
                const block = this.findBlockById(detail.field.slice(0, sep));

                if (block) this.appendPickedImages(block, detail.field.slice(sep + 1), detail.items || []);
            });
        },

        newId() {
            return 'block_' + Math.random().toString(36).slice(2, 11);
        },

        /**
         * Fill in attribute shapes the server defaults cannot express for the
         * editor inputs, and prepare any nested block lists.
         */
        prepareBlock(block) {
            block.attributes = block.attributes || {};
            const a = block.attributes;

            if (block.type === 'list' && Array.isArray(a.items)) {
                a.itemsRaw = a.items.join('\n');
            }

            if (block.type === 'heading' && typeof a.level === 'number') {
                a.level = 'h' + a.level;
            }

            if (block.type === 'poll' && !Array.isArray(a.options)) {
                a.options = [];
            }

            if (block.type === 'countdown' && typeof a.target_date === 'string') {
                a.target_date = a.target_date.slice(0, 16).replace(' ', 'T');
            }

            if (block.type === 'image') {
                a.images = Array.isArray(a.images) ? a.images : [];
                a.images.forEach((img) => {
                    img.src = img.src || '';
                    img.alt = img.alt || '';
                    if (img.caption === undefined) img.caption = null;
                });
            }

            if (block.type === 'gallery') {
                a.images = Array.isArray(a.images) ? a.images : [];
                a.images.forEach((img) => {
                    img.src = img.src || '';
                    img.alt = img.alt || '';
                    if (img.caption === undefined) img.caption = null;
                });
            }

            if (block.type === 'accordion') {
                a.items = Array.isArray(a.items) ? a.items : [];
                a.items.forEach((item) => {
                    item.title = item.title || '';
                    item.content = item.content || '';
                });
            }

            if (block.type === 'form') {
                a.fields = Array.isArray(a.fields) ? a.fields : [];
                a.fields.forEach((field) => {
                    field.name = field.name || '';
                    field.label = field.label || '';
                    field.type = field.type || 'text';
                    field.required = Boolean(field.required);
                    field.options = Array.isArray(field.options) ? field.options : [];
                    field.optionsRaw = field.options.join(', ');
                });
            }

            if (block.type === 'table') {
                a.headers = Array.isArray(a.headers) ? a.headers : [];
                a.rows = Array.isArray(a.rows) ? a.rows : [];
                a.rows = a.rows.map((row) => (Array.isArray(row) ? row : []));
            }

            if (block.type === 'columns') {
                a.columns = Array.isArray(a.columns) ? a.columns : [];
                a.column_widths = Array.isArray(a.column_widths) ? a.column_widths : [];

                while (a.column_widths.length < a.columns.length) a.column_widths.push(6);
                a.column_widths.length = a.columns.length;

                a.columns.forEach((column) => this.prepareBlockList(Array.isArray(column) ? column : []));
            }

            if (block.type === 'tabs') {
                a.tabs = Array.isArray(a.tabs) ? a.tabs : [];
                a.tabs.forEach((tab) => {
                    tab.label = tab.label || '';
                    tab.content = Array.isArray(tab.content) ? tab.content : [];
                    this.prepareBlockList(tab.content);
                });
            }

            if (block.type === 'carousel') {
                a.slides = Array.isArray(a.slides) ? a.slides : [];
                a.slides = a.slides.map((slide) => (Array.isArray(slide) ? slide : []));
                a.slides.forEach((slide) => this.prepareBlockList(slide));
            }
        },

        /**
         * Give every block in a nested list an id and a prepared attribute shape.
         */
        prepareBlockList(list) {
            list.forEach((block) => {
                if (!block.id) block.id = this.newId();

                this.prepareBlock(block);
            });
        },

        label(type) {
            return this.blockMeta[type]?.label || type;
        },

        isEditableType(type) {
            return Boolean(this.blockMeta[type]?.editable);
        },

        /**
         * Block types that expose an inline editor, in registry order.
         */
        editableTypes() {
            return Object.keys(this.blockMeta).filter((type) => this.isEditableType(type));
        },

        /**
         * Types offered inside a nested (depth-one) block list.
         */
        nestedEditableTypes() {
            return this.editableTypes().filter((type) => !NESTING_TYPES.includes(type));
        },

        /**
         * The nested block lists hosted by a block, by type.
         */
        nestedListsOf(block) {
            const a = block.attributes || {};

            if (block.type === 'columns') return Array.isArray(a.columns) ? a.columns : [];
            if (block.type === 'tabs') {
                return (Array.isArray(a.tabs) ? a.tabs : []).map((tab) => (Array.isArray(tab.content) ? tab.content : []));
            }
            if (block.type === 'carousel') return Array.isArray(a.slides) ? a.slides : [];

            return [];
        },

        findBlockById(id, list) {
            list = list || this.blocks;

            for (const block of list) {
                if (block.id === id) return block;

                for (const nested of this.nestedListsOf(block)) {
                    const found = this.findBlockById(id, nested);

                    if (found) return found;
                }
            }

            return null;
        },

        /**
         * Write a dotted/numeric attribute path, e.g. images.0.src, on a block.
         */
        setAttributePath(block, path, value) {
            const parts = String(path).split('.');
            let cursor = block.attributes;

            for (let i = 0; i < parts.length - 1; i++) {
                cursor = cursor?.[parts[i]];

                if (cursor === undefined || cursor === null) return;
            }

            cursor[parts[parts.length - 1]] = value;
        },

        /**
         * Read a dotted/numeric attribute path, e.g. images.0.src, on a block.
         */
        getAttributePath(block, path) {
            const parts = String(path).split('.');
            let cursor = block.attributes;

            for (const part of parts) {
                cursor = cursor?.[part];

                if (cursor === undefined || cursor === null) return null;
            }

            return cursor;
        },

        /**
         * Ask the media library picker to fill a block attribute. Pass
         * { multiple: true } to let the author pick a whole image set at once.
         */
        openPicker(block, path, options = {}) {
            window.dispatchEvent(new CustomEvent('open-media-picker', {
                detail: {
                    field: block.id + ':' + path,
                    multiple: Boolean(options.multiple),
                    max: options.max || IMAGE_SET_MAX,
                },
            }));
        },

        /**
         * Append media-library picks to a block's image array, skipping sources
         * already present and respecting the per-block image cap.
         */
        appendPickedImages(block, path, items) {
            const list = this.getAttributePath(block, path);

            if (!Array.isArray(list)) return;

            items.forEach((item) => {
                // Persist the disk-relative path so the reference survives a
                // host or driver change; fall back to the URL for older payloads.
                const src = item?.path || item?.url;

                if (!src || list.length >= IMAGE_SET_MAX) return;

                if (list.some((entry) => entry?.src === src)) return;

                list.push({ src, alt: item.alt || '', caption: null });
            });
        },

        defaults(type) {
            return JSON.parse(JSON.stringify(this.blockMeta[type]?.defaults || {}));
        },

        addBlock(type) {
            const block = { id: this.newId(), type: type, attributes: this.defaults(type) };

            this.prepareBlock(block);
            this.blocks.push(block);
        },

        /**
         * Add a block to a nested list held by an attribute path expression the view passes in.
         */
        makeNestedBlock(type) {
            const block = { id: this.newId(), type: type, attributes: this.defaults(type) };

            this.prepareBlock(block);

            return block;
        },

        removeNestedBlock(list, index) {
            const [block] = list.splice(index, 1);

            this.destroyEditor(block);
        },

        moveNestedBlock(list, index, direction) {
            const to = index + direction;

            if (to < 0 || to >= list.length) return;

            const [block] = list.splice(index, 1);

            list.splice(to, 0, block);
        },

        // Entry editors -----------------------------------------------------

        addImageSetEntry(block) {
            block.attributes.images = block.attributes.images || [];
            block.attributes.images.push({ src: '', alt: '', caption: null });
        },

        removeImageSetEntry(block, index) {
            block.attributes.images.splice(index, 1);
        },

        moveImageSetEntry(block, index, direction) {
            const list = block.attributes.images;
            const to = index + direction;

            if (to < 0 || to >= list.length) return;

            const [entry] = list.splice(index, 1);

            list.splice(to, 0, entry);
        },

        addGalleryImage(block) {
            block.attributes.images.push({ src: '', alt: '', caption: null });
        },

        removeGalleryImage(block, index) {
            block.attributes.images.splice(index, 1);
        },

        addAccordionItem(block) {
            block.attributes.items.push({ title: '', content: '' });
        },

        removeAccordionItem(block, index) {
            block.attributes.items.splice(index, 1);
        },

        addFormField(block) {
            const field = { name: '', label: '', type: 'text', required: false, options: [] };

            field.optionsRaw = '';
            block.attributes.fields.push(field);
        },

        removeFormField(block, index) {
            block.attributes.fields.splice(index, 1);
        },

        syncFieldOptions(block, index) {
            const raw = block.attributes.fields[index].optionsRaw || '';

            block.attributes.fields[index].options = raw
                .split(',')
                .map((option) => option.trim())
                .filter((option) => option !== '');
        },

        addTableColumn(block) {
            block.attributes.headers.push('Column ' + (block.attributes.headers.length + 1));
            block.attributes.rows.forEach((row) => row.push(''));
        },

        removeTableColumn(block, columnIndex) {
            block.attributes.headers.splice(columnIndex, 1);
            block.attributes.rows.forEach((row) => row.splice(columnIndex, 1));
        },

        addTableRow(block) {
            block.attributes.rows.push(block.attributes.headers.map(() => ''));
        },

        removeTableRow(block, rowIndex) {
            block.attributes.rows.splice(rowIndex, 1);
        },

        addLayoutColumn(block) {
            block.attributes.columns.push([]);
            block.attributes.column_widths.push(6);
        },

        removeLayoutColumn(block, index) {
            block.attributes.columns.splice(index, 1);
            block.attributes.column_widths.splice(index, 1);
        },

        addTab(block) {
            block.attributes.tabs.push({
                label: 'Tab ' + (block.attributes.tabs.length + 1),
                content: [this.makeNestedBlock('paragraph')],
            });
        },

        removeTab(block, index) {
            const [tab] = block.attributes.tabs.splice(index, 1);

            (tab.content || []).forEach((inner) => this.destroyEditor(inner));
        },

        addSlide(block) {
            block.attributes.slides.push([this.makeNestedBlock('paragraph')]);
        },

        removeSlide(block, index) {
            const [slide] = block.attributes.slides.splice(index, 1);

            (slide || []).forEach((inner) => this.destroyEditor(inner));
        },

        // Card toolbar --------------------------------------------------------

        duplicateBlock(index) {
            const copy = JSON.parse(JSON.stringify(this.blocks[index]));

            copy.id = this.newId();
            this.blocks.splice(index + 1, 0, copy);
        },

        removeBlock(index) {
            const [block] = this.blocks.splice(index, 1);

            this.destroyEditor(block);
            this.lastRemoved = { block: block, index: index };
        },

        undoRemove() {
            if (!this.lastRemoved) return;

            const { block, index } = this.lastRemoved;

            this.blocks.splice(Math.min(index, this.blocks.length), 0, block);
            this.lastRemoved = null;
        },

        moveBlock(index, direction) {
            this.moveBlockTo(index, index + direction);
        },

        moveBlockTo(from, to) {
            if (from === to || to < 0 || to >= this.blocks.length) return;

            const [block] = this.blocks.splice(from, 1);

            this.blocks.splice(to, 0, block);
            this.lastRemoved = null;
        },

        startDrag(index) {
            this.dragIndex = index;
        },

        hoverDrag(index) {
            if (this.dragIndex !== null && this.dragIndex !== index) {
                this.dropIndex = index;
            }
        },

        finishDrag() {
            if (this.dragIndex !== null && this.dropIndex !== null) {
                this.moveBlockTo(this.dragIndex, this.dropIndex);
            }

            this.dragIndex = null;
            this.dropIndex = null;
        },

        isCollapsed(block) {
            return this.collapsed.includes(block.id);
        },

        toggleCollapse(block) {
            this.collapsed = this.isCollapsed(block)
                ? this.collapsed.filter((id) => id !== block.id)
                : [...this.collapsed, block.id];
        },

        // Validation surfacing -------------------------------------------------

        isEmptyValue(value) {
            if (value === null || value === undefined) return true;
            if (typeof value === 'string') return value.trim() === '';
            if (Array.isArray(value)) return value.length === 0;

            return false;
        },

        /**
         * Entry-level fields the server rules mark required inside list attributes.
         */
        missingEntryFields(block) {
            const a = block.attributes || {};
            const missing = [];
            const checkEntries = (list, label, fields) => {
                (Array.isArray(list) ? list : []).forEach((entry, index) => {
                    fields.forEach((field) => {
                        if (this.isEmptyValue(entry?.[field])) missing.push(`${label} ${index + 1}: ${field}`);
                    });
                });
            };

            switch (block.type) {
                case 'gallery':
                    checkEntries(a.images, 'image', ['src', 'alt']);
                    break;
                case 'image':
                    checkEntries(a.images, 'image', ['src']);
                    break;
                case 'accordion':
                    checkEntries(a.items, 'item', ['title', 'content']);
                    break;
                case 'form':
                    checkEntries(a.fields, 'field', ['name', 'label']);
                    break;
                case 'table':
                    (Array.isArray(a.headers) ? a.headers : []).forEach((header, index) => {
                        if (this.isEmptyValue(header)) missing.push(`column ${index + 1}: name`);
                    });
                    (Array.isArray(a.rows) ? a.rows : []).forEach((row, rowIndex) => {
                        (Array.isArray(row) ? row : []).forEach((cell, cellIndex) => {
                            if (this.isEmptyValue(cell)) missing.push(`row ${rowIndex + 1}, cell ${cellIndex + 1}`);
                        });
                    });
                    break;
                case 'tabs':
                    (Array.isArray(a.tabs) ? a.tabs : []).forEach((tab, index) => {
                        if (this.isEmptyValue(tab?.label)) missing.push(`tab ${index + 1}: label`);
                        if (this.isEmptyValue(tab?.content)) missing.push(`tab ${index + 1}: content`);
                    });
                    break;
                case 'carousel':
                    (Array.isArray(a.slides) ? a.slides : []).forEach((slide, index) => {
                        if (this.isEmptyValue(slide)) missing.push(`slide ${index + 1}: content`);
                    });
                    break;
            }

            return missing;
        },

        /**
         * Required attributes, per the block class rules on the server, that are still empty.
         */
        missingFields(block) {
            const required = this.blockMeta[block.type]?.required || [];
            const top = required.filter((field) => this.isEmptyValue(block.attributes?.[field]));

            if (!this.isEditableType(block.type)) return top;

            return top.concat(this.missingEntryFields(block));
        },

        errorsFor(block) {
            return this.blockErrors[block.id] || [];
        },

        /**
         * Short text shown on the block card when the server would reject it.
         */
        invalidHint(block) {
            const missing = this.missingFields(block);

            if (missing.length) return 'Missing: ' + missing.join(', ');

            return this.errorsFor(block).length ? 'Needs attention' : '';
        },

        isInvalid(block) {
            return this.missingFields(block).length > 0 || this.errorsFor(block).length > 0;
        },

        /**
         * Return a CKEditor instance for a paragraph block to its textarea before the node is removed.
         */
        destroyEditor(block) {
            if (!window.CKEDITOR || !block) return;

            const instance = CKEDITOR.instances['paragraph-editor-' + block.id];

            if (instance) {
                instance.destroy(true);
            }
        },

        sync() {
            const input = document.querySelector(`input[name="${this.fieldName}"]`);
            if (input) input.value = JSON.stringify(this.blocks);

            window.dispatchEvent(new CustomEvent('blocks-changed', { detail: { count: this.blocks.length } }));
        },

        /**
         * Human labels for every block the server would reject, for the summary banner.
         */
        invalidBlockLabels() {
            return this.blocks
                .map((block, index) => (this.isInvalid(block) ? `#${index + 1} ${this.label(block.type)}` : null))
                .filter((label) => label !== null);
        },

        // Draft preview ---------------------------------------------------------

        async openPreview(wire) {
            this.previewLoading = true;
            this.previewOpen = true;
            this.previewHtml = await wire.renderPreview(JSON.stringify(this.blocks));
            this.previewLoading = false;
        },

        closePreview() {
            this.previewOpen = false;
            this.previewHtml = '';
        }
    }));
});

// Password Generator
window.generateSecurePassword = function(length = 16) {
    const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const lowercase = 'abcdefghijklmnopqrstuvwxyz';
    const numbers = '0123456789';
    const symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';

    let password = '';
    password += uppercase[Math.floor(Math.random() * length)];
    password += lowercase[Math.floor(Math.random() * length)];
    password += numbers[Math.floor(Math.random() * length)];
    password += symbols[Math.floor(Math.random() * length)];

    const all = uppercase + lowercase + numbers + symbols;
    for (let i = password.length; i < length; i++) {
        password += all[Math.floor(Math.random() * all.length)];
    }
    return password.split('').sort(() => 0.5 - Math.random()).join('');
};

document.addEventListener('DOMContentLoaded', () => {
    console.log('Admin JS: DOMContentLoaded');

    // The menu now scrolls inside its own column, so on a short screen the active link can
    // sit below that column with nothing indicating it. Bring it into view without touching
    // the content scroller. behavior is explicit because <html> sets scroll-behavior: smooth.
    document.querySelector('#desktop-sidebar .admin-nav-active')?.scrollIntoView({
        block: 'nearest',
        behavior: 'auto',
    });

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
            input.type = 'text';
            if (confirmInput) confirmInput.value = password;
            input.dispatchEvent(new Event('input', { bubbles: true }));
            if (confirmInput) confirmInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });
});

// Admin sort-order fields ask the server whether a typed number is still free and
// show the answer inline. The FormRequest repeats the same check on submit, so a
// stale answer here can never write a duplicate.
(() => {
    const latest = new WeakMap();

    const scopePairs = (input, form) =>
        (input.dataset.sortScopeInputs || '')
            .split(',')
            .filter(Boolean)
            .map((pair) => pair.split(':'))
            .map(([column, field]) => {
                const control = form ? form.querySelector(`[name="${field}"]`) : null;

                return control && control.value !== '' ? [column, control.value] : null;
            })
            .filter(Boolean);

    document.addEventListener('change', (event) => {
        if (! (event.target instanceof Element)) {
            return;
        }

        const input = event.target.closest('input[data-sort-check-url]');
        if (! input) {
            return;
        }

        const wrapper = input.closest('[data-sort-order-field]');
        const slot = wrapper ? wrapper.querySelector('[data-sort-error]') : null;
        if (! slot) {
            return;
        }

        const value = Number(input.value);

        // Blank or unusable: the model assigns the next number, so there is nothing
        // to complain about yet.
        if (! Number.isInteger(value) || value < 1) {
            slot.textContent = '';
            slot.hidden = true;

            return;
        }

        const url = new URL(input.dataset.sortCheckUrl, document.baseURI);
        url.searchParams.set('value', String(value));

        if (input.dataset.sortCurrent) {
            url.searchParams.set('current', input.dataset.sortCurrent);
        }

        scopePairs(input, input.closest('form')).forEach(([column, scopeValue]) => {
            url.searchParams.set(`scope[${column}]`, scopeValue);
        });

        const token = (latest.get(input) || 0) + 1;
        latest.set(input, token);

        fetch(url.toString(), {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        })
            .then((response) => (response.ok ? response.json() : null))
            .then((data) => {
                if (! data || latest.get(input) !== token) {
                    return;
                }

                slot.textContent = data.available ? '' : data.message;
                slot.hidden = Boolean(data.available);
            })
            .catch(() => {
                // Offline or aborted: submit-time validation still has the last word.
            });
    });
})();


/* One arrow-key walker, shared by every menu in the shell.

   The four bar popovers already had this behaviour inside `adminPopover`; the top-navigation
   branches need the same rule, and a second implementation is how two menus start disagreeing
   about where Up from the first row goes. Visibility is filtered here rather than by the
   caller, because a menu whose hidden rows are still in the tab order is a menu that appears to
   drop keys on the floor. */
const walkMenuRows = (root, delta) => {
    if (! root) {
        return;
    }

    const rows = Array.from(root.querySelectorAll('[role="menuitem"], [role="menuitemradio"]')).filter(
        (element) => element.offsetParent !== null,
    );

    if (rows.length === 0) {
        return;
    }

    const at = rows.indexOf(document.activeElement);
    const next = at === -1 ? (delta > 0 ? 0 : rows.length - 1) : (at + delta + rows.length) % rows.length;

    rows[next].focus();
};

/* --------------------------------------------------------------------------
   Admin sidebar: navigation that survives an SPA swap.

   The rail is persisted, so nothing inside it re-renders when a new page arrives —
   including the markup that decided which item was lit. Livewire's own navigate
   fixture solves that with a reactive href plus a $current magic, and this is that
   pattern widened to prefix matching: /admin/quotes/12 has to keep Quotes lit, and
   an exact href comparison would let it go dark one click after arriving.

   Longest prefix wins, because two real paths genuinely nest: /admin/gallery/settings
   starts with /admin/gallery, so matching the shorter one first would light Gallery
   while the user is reading Gallery Settings.

   Search works the same way, and for the same reason: it cannot ask the server, because
   asking the server is a navigation. So the rail publishes one blob — the paths it can
   reach and the words each row answers to — and both questions get answered against it.
   A menu entry therefore cannot be made unsearchable by forgetting a rule in a second
   language; adding the row to the config is the whole of the work.

   data-navigate-once matters more than it looks: without it the magic is re-registered
   after every body swap, each copy closing over its own reactive state, and only the
   newest one would still be listening.
   -------------------------------------------------------------------------- */
document.addEventListener('alpine:init', () => {
    /* Config is PULLED here rather than pushed, because the shell's inline script is a classic
       one and this module is deferred: on a fresh load the script runs first, and a call to a
       function that does not exist yet takes the whole blob with it. That is what was happening
       — `themeUrl`, `navUrl` and the search index were all still empty defaults, so the bar's
       theme POST was going to the current page and Ctrl+K could only ever find records.

       Either side may still arrive first, so both directions are honoured: `__ADMIN_NAV_BOOT__`
       is what the layout published, and `window.initAdminNav()` remains for the case where this
       module is already standing (a `wire:navigate`, where the layout script re-runs with the
       page that just arrived). */
    window.__ADMIN_NAV__ = Object.assign(
        { path: null, active: null, theme: 'auto', themeUrl: '', navUrl: '', searchUrl: '', text: {}, labels: {}, badges: {} },
        window.__ADMIN_NAV__ || {},
        window.__ADMIN_NAV_BOOT__ || {},
    );

    const cfg = window.__ADMIN_NAV__;

    const state = Alpine.reactive({
        theme: cfg.theme || 'auto',
        path: window.location.pathname,
        lit: cfg.active || window.location.pathname,
        // Read from the class the layout put on <html> before paint, exactly as $store.sidebar
        // does for the rail width: one written fact, so the controls and the CSS cannot disagree
        // about which navigation is current on the first render.
        navTop: document.documentElement.classList.contains('admin-nav-top'),
    });

    const strip = (subject) => String(subject ?? '/').replace(/\/+$/, '');

    const navText = () => cfg.text || {};

    /* The row that owns a path, by the longest match: /admin/quotes/12 is Quotes, and
       /admin/gallery/settings is Gallery Settings rather than Gallery even though both are
       prefixes of it. Sorted once at boot, because this runs on every navigation and the index
       is small. */
    const longestPrefix = (path) => {
        const here = strip(path);

        return (
            Object.keys(navText())
                .map(strip)
                .sort((a, b) => b.length - a.length)
                .find((target) => here === target || (target !== '' && here.startsWith(`${target}/`))) ?? here
        );
    };

    const termsOf = (query) => String(query).trim().toLowerCase().split(/\s+/).filter(Boolean);

    const escapeHtml = (value) => String(value).replace(/[&<>"]/g, (character) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
    })[character]);

    const matchesPrefix = (href) => {
        const target = strip(href);

        if (target === '' || target === '/') {
            return strip(state.path) === '/';
        }

        return strip(state.path) === target || strip(state.path).startsWith(`${target}/`);
    };

    Alpine.magic('current', () => (expected) => matchesPrefix(expected));

    // Every group mounted on the page, so a navigation can reopen the branch the new
    // page lives in. A persisted rail keeps its Alpine roots alive across swaps, which
    // is exactly why nothing else would notice the arrival.
    const navGroups = new Set();

    /* One remembered open/closed per group, keyed by its label. The key is worth naming
       rather than inlining: the same string is read on every group's init, and a typo in a
       literal here silently means "the sidebar forgets everything" with no error anywhere. */
    const REMEMBER_KEY = 'adminSidebarGroups';

    const readRemembered = () => {
        try {
            const parsed = JSON.parse(window.localStorage.getItem(REMEMBER_KEY) || '{}');

            return parsed && typeof parsed === 'object' ? parsed : {};
        } catch (cause) {
            // Safari in private mode throws on the read, not just the write.
            return {};
        }
    };

    /* The server renders from a cookie because a first paint driven only by localStorage would
       open every branch that holds the page, then snap shut the ones the person closed — a
       flicker that reads as a bug rather than as a restore. Both write here so the two copies
       cannot drift into different shapes. */
    const writeRemembered = (remembered) => {
        try {
            window.localStorage.setItem(REMEMBER_KEY, JSON.stringify(remembered));
            document.cookie = `${REMEMBER_KEY}=${encodeURIComponent(JSON.stringify(remembered))}; path=/; max-age=31536000; SameSite=Lax`;
        } catch (cause) {
            // Nothing further to do: the group still opens, it just forgets next time.
        }
    };

    Alpine.store('adminNav', {
        /* The single lit path. Rows compare themselves to it rather than each walking the
           prefix list, so one navigation costs one pass over the index instead of one per
           row per column — and every copy of a row in the rail agrees by construction. */
        get lit() {
            return state.lit;
        },

        get theme() {
            // Held in the reactive state rather than read from cfg: cfg is a plain
            // window object, and a getter over a non-reactive field never re-runs, so the
            // theme chip would keep printing whatever the page was rendered with.
            return state.theme;
        },

        /* The two words the top bar prints for the current page. Answered from `lit` rather
           than from the location so the bar and the rail can never disagree: they are reading
           the same single fact, recomputed by the same sync() on the same navigation.

           Nulls mean no menu row owns this path. The bar then shows the business name alone —
           the alternative is a label invented from a URL, which would be a claim about a page
           the navigation does not know. */
        get context() {
            const entry = (cfg.labels || {})[state.lit];

            return entry ?? { group: null, label: null };
        },

        // The theme word the trigger's aria-label reads, spelled as the menu spells it.
        label() {
            return { light: 'Light', dark: 'Dark', auto: 'System' }[state.theme] ?? 'System';
        },

        /* Called by the rail's boot script on a full load, when the server has settled both
           questions — where we are, and which row owns it. */
        boot() {
            state.theme = cfg.theme ?? state.theme;
            state.path = window.location.pathname;
            state.lit = cfg.active ?? strip(state.path);
        },

        /* Called after livewire:navigate swaps the page. The boot script does not run again
           inside a persisted island, so the rail has to answer both questions itself, and the
           only new fact is the location: the row that owns it is the longest known prefix. */
        sync() {
            state.path = window.location.pathname;
            state.lit = longestPrefix(state.path);
        },

        cycleTheme() {
            const order = ['auto', 'light', 'dark'];
            const next = order[(order.indexOf(state.theme) + 1) % order.length];

            this.setTheme(next);
        },

        /* The dark class and both localStorage keys are written exactly as the pre-paint seed
           in the layout writes them; the layout reads them before Flux does, so a third
           spelling would have the two disagree on the next load. The POST keeps the preference
           on the account rather than only on this browser, which is what the person who set it
           from an admin page on a second machine expects. */
        setTheme(theme) {
            cfg.theme = theme;
            state.theme = theme;

            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            document.documentElement.classList.toggle(
                'dark',
                theme === 'dark' || (theme === 'auto' && prefersDark),
            );

            window.localStorage.setItem('theme', theme);
            window.localStorage.setItem('flux.appearance', theme === 'auto' ? 'system' : theme);

            // The rail publishes this URL with the rest of its index; there is no global
            // route() helper in this app and inventing one for a single POST is not worth it.
            window.fetch(cfg.themeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ theme }),
            }).catch(() => {
                // The preference is already applied locally; the write can wait for the next try.
            });
        },

        /* ------------------------------------------------------------------
           Navigation layout.

           The theme did this first and the shape is copied on purpose: apply locally, then POST
           so the choice is waiting on the second machine too. `syncLayout()` exists because the
           class on <html> is the one fact both sides read — the layout seeds it before paint on a
           full load, and its body-level twin re-asserts it after every `wire:navigate`. That is
           how a switch costs neither a navigation nor a re-render, and how a choice made
           elsewhere lands without waiting for a reload.
           ------------------------------------------------------------------ */
        get navTop() {
            // Through the reactive state rather than straight off the DOM: a getter that reads a
            // class is not a dependency anything can invalidate, so the control would print
            // whatever mode the page was rendered with and never update.
            return state.navTop;
        },

        // The same fact spelled the way the enum spells it, so a control can compare against the
        // value it was rendered with instead of inventing its own boolean.
        get navLayout() {
            return state.navTop ? 'top' : 'sidebar';
        },

        syncLayout() {
            state.navTop = document.documentElement.classList.contains('admin-nav-top');
        },

        setNavLayout(layout) {
            const top = layout === 'top';

            state.navTop = top;
            document.documentElement.classList.toggle('admin-nav-top', top);

            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    type: 'success',
                    message: top
                        ? 'Admin navigation moved to the top bar. Phones and tablets keep the slide-out menu.'
                        : 'Admin navigation moved back to the sidebar. Phones and tablets keep the slide-out menu.',
                },
            }));

            window.fetch(cfg.navUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ admin_nav_layout: layout }),
            }).catch(() => {
                // Same concession as the theme: the panel already looks the way it was asked to,
                // and a lost write is retried the next time the control is used.
            });
        },
    });

    document.addEventListener('livewire:navigated', () => {
        queueMicrotask(() => {
            // A new page means a new lit row, and the rail cannot learn that by itself: it was
            // not re-rendered, and nothing asks the server again.
            Alpine.store('adminNav').sync();

            navGroups.forEach((group) => {
                if (group.syncOnNavigate()) {
                    group.open = true;
                }
            });

            // The menu scrolls inside its own column, so on a short screen the lit item can
            // sit below that column with nothing indicating it. behaviour is explicit because
            // <html> sets scroll-behavior: smooth and a nav jump should not glide.
            document.querySelectorAll('.admin-nav-scroll').forEach((column) => {
                column.querySelector('[data-admin-nav-active="true"]')?.scrollIntoView({
                    block: 'nearest',
                    behavior: 'auto',
                });
            });
        });
    });

    window.Alpine.data('adminNavGroup', (definition = {}) => ({
        open: true,
        key: definition.key || '',
        serverHasActive: definition.serverHasActive === true,

        // Only the desktop column can shrink to an 80px rail; the drawer is always full width
        // and must not reach into a store whose state it does not reflect.
        rail: definition.rail === true,

        /* The row the rail considers current, published as a one-item list so the branch markup
           can ask "do I contain it" with a plain method call. Exposed as a getter to keep it
           reactive across a navigation. */
        get litPaths() {
            return [Alpine.store('adminNav').lit];
        },

        init() {
            const remembered = readRemembered();

            // An unlisted group keeps whatever the server rendered — which is open when it
            // holds the active item, so a first visit lands with the right branch expanded.
            this.open = Object.prototype.hasOwnProperty.call(remembered, this.key)
                ? remembered[this.key] === true
                : definition.open !== false;

            navGroups.add(this);
        },

        destroy() {
            navGroups.delete(this);
        },

        toggle() {
            // A collapsed rail hides every branch body by CSS, so a plain toggle there would be
            // a click with no visible outcome and no way to reach the rows. Widening the rail and
            // opening the branch the person aimed at is what the old rail did; keep doing it.
            if (this.rail && Alpine.store('sidebar').collapsed) {
                Alpine.store('sidebar').toggle();
                this.open = true;
                writeRemembered(Object.assign(readRemembered(), { [this.key]: true }));

                return;
            }

            this.open = ! this.open;

            const remembered = readRemembered();
            remembered[this.key] = this.open;
            writeRemembered(remembered);
        },

        // True when this navigation landed somewhere this branch owns. Only ever opens,
        // because silently shutting a branch the user left open is worse than one that
        // stays open after they leave it.
        syncOnNavigate() {
            return Array.from(this.$el.querySelectorAll('[data-nav-href]')).some((link) =>
                matchesPrefix(link.getAttribute('data-nav-href')),
            );
        },

    }));

    /* ---------------------------------------------------------------------
       Top bar.

       Each piece answers one question the bar cannot answer by itself. They are
       Alpine rather than Blade because the bar is a persisted island: anything it
       needs to know after a wire:navigate has to be computed in the browser, and
       the only browser-side facts it is given are the navigation index the rail
       publishes and the location.
       --------------------------------------------------------------------- */

    /* One popover for the bar's three menus — waiting queues, theme, and account. Create is not
       in this list: it opens a dialog, because its tiles want a title and a description each.
       They were four hand-written copies in the sidebar footer, which is how three of them came
       to disagree about whether Escape returned focus. The trigger is reached through x-ref
       rather than a query because the panel is a sibling of the button, and a $el-scoped
       selector would find whichever of the two happened to be written first. */
    window.Alpine.data('adminPopover', () => ({
        open: false,

        toggle() {
            this.open ? this.close() : this.show();
        },

        show(fromEnd = false) {
            this.open = true;

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    const items = this.items();
                    const item = fromEnd ? items.at(-1) : items[0];

                    item?.focus();
                });
            });
        },

        close(refocus = true) {
            if (! this.open) {
                return;
            }

            this.open = false;

            if (refocus) {
                this.$refs.trigger?.focus();
            }
        },

        items() {
            return Array.from(
                this.$el.querySelectorAll('[role="menuitem"], [role="menuitemradio"]'),
            ).filter((element) => element.offsetParent !== null);
        },

        /* Arrow keys walk the menu and wrap, because a menu long enough to need scrolling is a
           menu whose end is a reason to keep going rather than a wall. Separators are skipped:
           they carry the role for assistive tech but nothing to activate. The rule lives in one
           function so the top-navigation branches cannot drift from the bar's own menus. */
        move(delta) {
            walkMenuRows(this.$el, delta);
        },
    }));

    /* One drawer, one dialog, and now a rail toggle that has to know which of the two it is
       standing next to. The stores keep the state; this only translates a press into the verb
       for the current viewport, so the control can be one button instead of two. */
    window.Alpine.data('adminSidebarToggle', () => ({
        isDesktop: () => window.matchMedia('(min-width: 1024px)').matches,

        collapsed: () => Alpine.store('sidebar').collapsed,

        open: () => (window.matchMedia('(min-width: 1024px)').matches
            ? ! Alpine.store('sidebar').collapsed
            : Alpine.store('mobileMenu').open),

        label: () => (window.matchMedia('(min-width: 1024px)').matches
            ? (Alpine.store('sidebar').collapsed ? 'Expand navigation' : 'Collapse navigation')
            : 'Open navigation'),

        toggle() {
            if (this.isDesktop()) {
                Alpine.store('sidebar').toggle();

                return;
            }

            Alpine.store('mobileMenu').toggle();
        },
    }));

    /* ---------------------------------------------------------------------
       Top navigation.

       One Alpine root for the whole menu row, because both of its questions belong to
       the row rather than to a branch: which panel is open, and how many branches the
       measured width can actually hold. Keys follow the ARIA menu bar pattern — Left and
       Right between triggers, Down into the panel, Escape back out to the trigger that
       opened it, Home and End at the ends — and the rows inside the panels are the same
       row component the rail and the drawer print, so "which row is the current page"
       stays one computation shared by all three.

       Hover opens with intent and closes with a delay. Both halves matter: a pointer
       crossing the bar on its way somewhere else must not throw every panel open in
       turn, and a diagonal move from a trigger down into its own panel must not close
       the panel it was aimed at.
       --------------------------------------------------------------------- */
    window.Alpine.data('adminMenuBar', () => ({
        open: null,
        fit: null,
        forcing: false,
        slots: [],
        widths: [],
        moreWidth: 0,
        gap: 0,
        measured: false,
        measuring: false,
        remeasure: false,
        observer: null,
        timer: null,

        HOVER_OPEN_MS: 120,
        HOVER_CLOSE_MS: 250,

        init() {
            this.slots = Array.from(this.$el.querySelectorAll('[data-menu-slot]'));
            this.gap = parseFloat(window.getComputedStyle(this.$el).columnGap) || 0;

            /* The row's own width is the budget, and a ResizeObserver is the only honest way to
               watch it: this bar can lose room to the window, to the context chip taking a longer
               page name, or to a language that spells a section name wider — and only one of those
               is a window resize. Until a measurement exists, a size change is the measurement
               request: the row below `lg` is `display: none`, so the tick that makes it visible is
               the first one that can answer anything. */
            this.observer = new ResizeObserver(() => {
                this.measured ? this.layout() : this.measure();
            });
            this.observer.observe(this.$el);

            this.measure();

            /* `display=swap` means the first measurement is taken in the fallback face, which is
               not the same width as Geist. Re-measuring when the real one lands is what stops a
               row that fitted at first paint from spilling past the search box a moment later. */
            document.fonts?.ready.then(() => this.measure());
        },

        destroy() {
            this.observer?.disconnect();
            window.clearTimeout(this.timer);
        },

        /* Measure with everything visible, then decide what to hide. A width read off a hidden
           slot answers zero, which is how an overflow menu ends up holding the entire navigation
           after a window drag. */
        async measure() {
            /* Zeroes are not a small measurement, they are no measurement: a row that recorded
               them concludes that all seven groups fit in anything, and then prints them at their
               natural width straight through the search field and the tools cluster. Declining here
               is safe because whatever makes the row laid out also fires the observer, which asks
               again. */
            if (this.$el.clientWidth === 0) {
                this.measured = false;

                return;
            }

            /* The observer and the font promise can both ask at once, and two runs interleaved over
               one `await` hand the second one a row the first has already un-hidden — which is a
               fresh set of zeroes written over good numbers. The later ask is remembered, not
               dropped, because it is the one that saw the newest size. */
            if (this.measuring) {
                this.remeasure = true;

                return;
            }

            this.measuring = true;
            this.forcing = true;
            this.$el.classList.add('is-measuring');

            await this.$nextTick();

            this.widths = this.slots.map((slot) => slot.getBoundingClientRect().width);
            this.moreWidth = this.$el.querySelector('[data-menu-more]')?.getBoundingClientRect().width || 0;

            this.$el.classList.remove('is-measuring');
            this.forcing = false;
            this.measuring = false;

            this.measured = this.widths.some((width) => width > 0);

            this.layout();

            if (this.remeasure) {
                this.remeasure = false;

                await this.measure();
            }
        },

        layout() {
            if (this.forcing || this.widths.length === 0) {
                return;
            }

            const room = Math.floor(this.$el.clientWidth);

            if (room <= 0) {
                return;
            }

            const natural = this.widths.reduce((sum, width) => sum + width, 0) + this.gap * (this.widths.length - 1);

            if (natural <= room) {
                this.fit = this.widths.length;

                return;
            }

            /* Something has to move, so More has to be affordable before it is chosen: reserve its
               own width plus the gap that would sit in front of it. Fitting first and discovering
               afterwards that there was no room left for the pill is the one way this row can hide
               navigation entirely. */
            const budget = room - this.moreWidth - this.gap;
            let used = 0;
            let fit = 0;

            for (const width of this.widths) {
                const step = used + width + (used > 0 ? this.gap : 0);

                if (step > budget) {
                    break;
                }

                used = step;
                fit += 1;
            }

            this.fit = fit;
        },

        /* Which slots stay in the row. Nothing is hidden before the first measurement, so a
           browser without script — or one that threw on the way here — still shows the whole
           navigation instead of quietly dropping the tail. */
        shows(index) {
            return this.forcing || this.fit === null || index < this.fit;
        },

        hasOverflow() {
            if (this.forcing) {
                // Laid out but invisible while measured: see `.is-measuring` in admin.css.
                return true;
            }

            return this.fit !== null && this.fit < this.slots.length;
        },

        overflows(index) {
            return this.hasOverflow() && this.fit !== null && index >= this.fit;
        },

        isOpen(key) {
            return this.open === key;
        },

        toggle(key) {
            this.open === key ? this.close(true) : this.openBranch(key);
        },

        openBranch(key) {
            window.clearTimeout(this.timer);
            this.open = key;
        },

        close(refocus) {
            if (this.open === null) {
                return;
            }

            const previous = this.open;

            this.open = null;

            if (refocus) {
                this.triggerOf(previous)?.focus();
            }
        },

        /* Down and Enter both end up in the panel, so they do the same thing: open it and put the
           cursor on its first row. The row is `display: none` until `x-show` has written over the
           state change, and a focus attempt before that is silently dropped — one microtask is not
           enough, because the directive's effect and this callback are queued in the same flush and
           the row loses. Waiting for the next frame asks after the paint has happened, which is the
           point where the row is real. A keydown means the tab is visible, so that frame arrives. */
        openAndEnter(key) {
            this.openBranch(key);

            window.requestAnimationFrame(() => {
                this.panelOf(key)?.querySelector('[data-nav-href]')?.focus();
            });
        },

        /* Hover state belongs to the branch, which is the trigger and its panel together. Read off
           the trigger alone it was wrong in a way that looked like the menu closing on its own: the
           panel is the trigger's *sibling*, so moving down into the panel left the trigger, started
           this countdown, and nothing inside the panel could call it back — the submenu shut under
           the pointer about 250ms after it arrived. On the branch, crossing that gap never leaves
           the element being hovered, and the countdown starts only once the pointer has gone. */
        hoverIn(key, event) {
            if (!this.isMouse(event)) {
                return;
            }

            window.clearTimeout(this.timer);
            this.timer = window.setTimeout(() => this.openBranch(key), this.HOVER_OPEN_MS);
        },

        hoverOut(event) {
            if (!this.isMouse(event)) {
                return;
            }

            window.clearTimeout(this.timer);
            // Longer leaving than arriving: sliding sideways to the next section passes through the
            // page behind the bar for a frame, and a menu that shuts mid-crossing is a menu nobody
            // can click.
            this.timer = window.setTimeout(() => this.close(false), this.HOVER_CLOSE_MS);
        },

        /* There is no pointer-type modifier in Alpine — unrecognised modifiers are dropped unless
           the event is a key or click one — so the `.pointer-type-mouse` this intent used to be
           spelled with filtered nothing. A tap was therefore a hover: pointerenter, then
           pointerleave as soon as the touch pointer was lifted, which opened the branch on the
           click and closed it again a quarter second later on any touch machine wide enough to
           show the bar. */
        isMouse(event) {
            return event?.pointerType === 'mouse';
        },

        walk(delta) {
            walkMenuRows(document.activeElement?.closest('[role="menu"]'), delta);
        },

        /* Left and Right move along the triggers and wrap, and close whatever is open on the way,
           because an arrow that leaves a panel standing behind it is a panel nobody pressed for. */
        moveTrigger(delta) {
            const list = this.triggers();

            if (list.length === 0) {
                return;
            }

            const at = list.indexOf(document.activeElement);

            let next;

            if (delta === 'first') {
                next = 0;
            } else if (delta === 'last') {
                next = list.length - 1;
            } else {
                next = at === -1 ? (delta > 0 ? 0 : list.length - 1) : (at + delta + list.length) % list.length;
            }

            this.close(false);
            list[next].focus();
        },

        triggerOf(key) {
            return this.$el.querySelector(`[data-menu-trigger="${key}"]`);
        },

        panelOf(key) {
            return this.$el.querySelector(`[data-menu-panel="${key}"]`);
        },

        triggers() {
            return Array.from(this.$el.querySelectorAll('[data-menu-trigger]')).filter(
                (element) => element.offsetParent !== null,
            );
        },

        /* Which branch owns the page: the section name the shell already resolved for the lit row,
           compared with the section this branch is labelled. One answer, computed once, shared by
           the context chip, the palette and the bar — which is why this does not walk the rows and
           match paths here. A persisted island is restored from Livewire's store after Alpine has
           walked it, so a subtree query made on the first paint can legitimately see nothing, and
           a branch that reads its own children would stay dark until something made it look again. */
        holdsPage(index) {
            const slot = this.$el.querySelector(`[data-menu-slot="${index}"]`);

            if (! slot) {
                return false;
            }

            return slot.dataset.menuGroup === Alpine.store('adminNav').context.group;
        },
    }));

    /* Full screen is the browser's state, not the page's, so the store only mirrors it. The
       listener is registered here rather than inside the store because a store gets no init hook
       — and Esc leaves full screen without telling either of the two buttons that offer it. */
    const fullscreen = Alpine.reactive({
        on: document.fullscreenElement !== null,

        toggle() {
            if (document.fullscreenElement !== null) {
                document.exitFullscreen?.();

                return;
            }

            // Rejected when the document is not focused or the browser refuses; either way the
            // change event never fires, so `on` stays false and the icon stays honest.
            document.documentElement.requestFullscreen?.().catch(() => {});
        },
    });

    document.addEventListener('fullscreenchange', () => {
        fullscreen.on = document.fullscreenElement !== null;
    });

    Alpine.store('fullscreen', fullscreen);

    /* The command palette.
       Pages are matched here, in the browser, against the index the rail publishes — the same
       rule the old inline field used (every word must land somewhere in the row's haystack, in
       any order), so a destination that was findable before is findable now. Records come from
       admin.search, debounced, and only for the modules the account can open. */
    Alpine.store('adminPalette', {
        open: false,
        query: '',
        cursor: 0,
        loading: false,
        records: [],
        recent: readPaletteRecents(),
        token: 0,
        timer: null,

        RECENT_KEY: PALETTE_RECENT_KEY,
        MINIMUM: 2,

        show() {
            // The create dialog shares the palette's stacking level, so the two must not be up
            // at once — Ctrl+K is reachable from inside the dialog.
            Alpine.store('adminCreate')?.hide(false);

            this.open = true;
            this.cursor = 0;
        },

        hide() {
            this.open = false;
            this.query = '';
            this.records = [];
            this.cursor = 0;
        },

        isEmpty() {
            return this.query.trim() === '';
        },

        /* Every word has to land, on any field, in any order — so "gallery category" finds
           Gallery Categories while "settings cache" finds nothing. The group name is in the
           haystack, which is why typing "blog" reveals the whole branch. */
        matchesPage(href) {
            const text = navText()[href] || '';

            return termsOf(this.query).every((term) => text.includes(term));
        },

        pages() {
            if (this.isEmpty()) {
                return [];
            }

            return Object.entries(navText())
                .filter(([href]) => this.matchesPage(href))
                .map(([href]) => {
                    const entry = (cfg.labels || {})[href] || {};

                    return {
                        label: entry.label || href,
                        sub: entry.group || '',
                        url: href,
                        badge: null,
                    };
                })
                .sort((a, b) => a.label.localeCompare(b.label));
        },

        /* The flat list the arrow keys walk, built once per read so the on-screen order and the
           cursor can never disagree — each row is tagged with the index it is drawn at. */
        groups() {
            const pages = this.pages();
            const built = [];

            if (pages.length > 0) {
                built.push({ label: 'Pages', items: pages });
            }

            this.records.forEach((group) => {
                built.push({
                    label: group.group,
                    items: group.items.map((item) => ({ ...item, badge: null })),
                });
            });

            let cursor = 0;

            return built.map((group) => ({
                label: group.label,
                items: group.items.map((item) => ({ ...item, idx: cursor++ })),
            }));
        },

        total() {
            return this.groups().reduce((carry, group) => carry + group.items.length, 0);
        },

        at(index) {
            return this.groups().flatMap((group) => group.items)[index] ?? null;
        },

        move(delta) {
            const count = this.total();

            if (count === 0) {
                return;
            }

            // Wraps rather than stops, because the list is long enough that reaching the end is
            // a reason to keep going down, not a wall.
            this.cursor = (this.cursor + delta + count) % count;
        },

        status() {
            if (! this.open) {
                return '';
            }

            if (this.isEmpty()) {
                return this.recent.length > 0 ? 'Recent searches shown.' : 'Type to search.';
            }

            return `${this.total()} results for ${this.query}.`;
        },

        select() {
            const item = this.at(this.cursor);

            if (item) {
                this.choose(item);
            }
        },

        choose(item) {
            this.remember(this.query);
            this.hide();

            // Livewire.navigate keeps the rail and the bar standing; a bare location.href would
            // reload both, and the whole point of the persisted shell is that they do not move.
            if (window.Livewire?.navigate) {
                window.Livewire.navigate(item.url);

                return;
            }

            window.location.href = item.url;
        },

        remember(term) {
            const trimmed = String(term || '').trim();

            if (trimmed === '') {
                return;
            }

            this.recent = [trimmed, ...this.recent.filter((existing) => existing !== trimmed)].slice(0, 6);

            try {
                window.localStorage.setItem(this.RECENT_KEY, JSON.stringify(this.recent));
            } catch (cause) {
                // The history is a convenience; losing the write loses the convenience only.
            }
        },

        /* The record lookup, debounced.
           The token is the guard rather than a timestamp: a keystroke whose response arrives after
           the box has moved on must not be able to draw itself, and closing the dialog has to
           count as moving on. Pages never wait for this — they are matched in the browser, so the
           palette answers the moment it opens even with the network down. */
        run(term) {
            const mine = ++this.token;
            const trimmed = String(term || '').trim();

            this.cursor = 0;
            this.records = [];

            if (trimmed.length < this.MINIMUM) {
                this.loading = false;

                return;
            }

            this.loading = true;

            window.clearTimeout(this.timer);
            this.timer = window.setTimeout(async () => {
                try {
                    const response = await fetch(`${cfg.searchUrl}?q=${encodeURIComponent(trimmed)}`, {
                        headers: { Accept: 'application/json' },
                    });

                    if (! response.ok || mine !== this.token) {
                        return;
                    }

                    const body = await response.json();

                    this.records = Array.isArray(body.groups) ? body.groups : [];
                } catch (cause) {
                    // A dropped lookup leaves the page results, which needed no server.
                } finally {
                    if (mine === this.token) {
                        this.loading = false;
                    }
                }
            }, 200);
        },
    });

    /* The dialog's own root: it owns the input ref and the watcher, because $watch belongs to an
       Alpine scope and the store is not one. Everything the person sees is still read from the
       store, so the bar's trigger and this dialog can never disagree about whether it is open. */
    window.Alpine.data('adminPaletteDialog', () => ({
        trap: trapTab,

        init() {
            this.$watch('$store.adminPalette.query', (value) => {
                Alpine.store('adminPalette').run(value);
            });
        },
    }));

    /* The create dialog's flag, kept in a store for the same reason the palette's is: two
       controls open it (the bar's `+` and the account menu's phone row) and one panel shows it,
       and none of the three share an Alpine scope. `returnTo` is the element that opened it, so
       Escape and the backdrop put focus back where the person was rather than on the body. A
       navigation closes it without refocusing: the page under it is about to be replaced. */
    Alpine.store('adminCreate', {
        open: false,
        returnTo: null,

        show(trigger = null) {
            this.returnTo = trigger instanceof HTMLElement ? trigger : document.activeElement;
            this.open = true;
        },

        hide(refocus = true) {
            if (! this.open) {
                return;
            }

            this.open = false;

            if (refocus) {
                this.returnTo?.focus?.();
            }

            this.returnTo = null;
        },
    });

    window.Alpine.data('adminCreateDialog', () => ({
        trap: trapTab,

        focusFirst() {
            this.$el.querySelector('[role="menuitem"]')?.focus();
        },

        /* The tiles are a grid, but they read as one list: arrows walk it in document order and
           wrap, the same rule the bar's drop-down menus use. */
        move(delta) {
            walkMenuRows(this.$el, delta);
        },
    }));
});

window.initAdminNav = (config) => {
    // Whichever side arrives first, the other reads from the same object afterwards.
    window.__ADMIN_NAV__ = Object.assign(window.__ADMIN_NAV__ || {}, config);

    const store = Alpine.store?.('adminNav');

    if (store) {
        store.boot();
    }
};

document.addEventListener('DOMContentLoaded', () => {
    /* Both keys now open the command palette. They used to focus the sidebar's inline filter,
       which the palette replaces: one shortcut, one behaviour, and a box that searches the
       records as well as the rows.

       Registered here rather than in the Alpine scope because the bar is a persisted island and
       a directive on it would be re-added on every full load while surviving every navigation —
       which is exactly how a shortcut ends up firing twice. The window listener is added once
       per document, and the store it writes to outlives both. */
    const openPalette = (event) => {
        event.preventDefault();
        Alpine.store('adminPalette').show();
    };

    // Cmd+K on a Mac, Ctrl+K everywhere else — picked over a bare "/" as the primary because
    // it is the muscle memory people already carry for "search this app".
    window.addEventListener('keydown', (event) => {
        if ((event.metaKey || event.ctrlKey) && String(event.key).toLowerCase() === 'k') {
            openPalette(event);
        }
    });

    // "/" is the weaker of the two and must stay out of the way of whoever is typing, so it
    // only reaches for the palette when focus is not already inside a field. That also leaves
    // Firefox quick-find working on any page where the palette is not the target.
    window.addEventListener('keydown', (event) => {
        if (event.key !== '/' || event.metaKey || event.ctrlKey || event.altKey) {
            return;
        }

        const active = document.activeElement;
        const typing = ['INPUT', 'TEXTAREA', 'SELECT'].includes(active?.tagName || '')
            || active?.isContentEditable === true;

        if (! typing) {
            openPalette(event);
        }
    });
});

/* The rail's boot script and Livewire's Alpine may initialise in either order — a module
   script loaded in the head waits for the parser, an inline body script does not — so both
   sides check for the other instead of assuming. Anything queued here runs the moment Alpine
   is ready, before its first tree walk. */
document.addEventListener('alpine:initialized', () => {
    const store = Alpine.store?.('adminNav');

    if (store && window.__ADMIN_NAV__ && window.__ADMIN_NAV__.active) {
        store.boot();
    }
});

/* ============================================================
   THE DAY DESK — /dashboard
   ------------------------------------------------------------
   One module, one clock. It does three things and nothing else: it advances the
   now line and the masthead clock from the real time, it rolls a figure up when
   its section is first read, and it lets each section and each span arrive.
   Everything it touches is already correct in the HTML the server sent, so a
   page without JavaScript is a complete page, and `prefers-reduced-motion` never
   starts the loop at all.

   The engine this project would normally reach for is GSAP, and GSAP is
   deliberately not installed: one page is not worth a dependency in the bundle
   and the deploy. The rule that engine exists to enforce is kept anyway — there
   is exactly one rAF loop here, it is the only clock, and it stops when the desk
   leaves the page.
   ============================================================ */

const deskPrefersReduced = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const deskPad = (value) => String(value).padStart(2, '0');

/**
 * The clock text, in whichever of the two shapes the app's own format uses:
 * 24-hour, or 12-hour with a meridiem and a padded hour, which is what a PHP
 * 'h:i A' prints. Takes a minute of the day and gives back what a visitor reads.
 */
function deskClockText(minuteOfDay, use24) {
    const total = Math.max(0, Math.floor(minuteOfDay)) % 1440;
    const hour = Math.floor(total / 60);
    const minute = total % 60;

    if (use24) {
        return `${deskPad(hour)}:${deskPad(minute)}`;
    }

    const suffix = hour >= 12 ? 'PM' : 'AM';
    const twelve = hour % 12 === 0 ? 12 : hour % 12;

    return `${deskPad(twelve)}:${deskPad(minute)} ${suffix}`;
}

/**
 * Start the desk over the page currently in the shell.
 *
 * @returns {Function|null} what to undo when the desk navigates away
 */
function bootDayDesk() {
    const root = document.querySelector('[data-dash]');

    if (! root) {
        return null;
    }

    if (deskPrefersReduced()) {
        /* The server has already placed the line, chosen the day's state and
           written every figure. Under reduced motion that is the page, and it
           stays the page: no loop, nothing hidden waiting to be shown. */
        return null;
    }

    const rail = root.querySelector('[data-dash-rail]');
    const daybar = root.querySelector('[data-dash-daybar]');
    const clocks = [...root.querySelectorAll('[data-dash-clock]')];
    const state = root.querySelector('[data-dash-state]');
    const reveals = [...root.querySelectorAll('[data-dash-reveal]')];
    const scroller = document.querySelector('main.admin-main');

    const openMinute = Number.parseFloat(root.dataset.openMinute ?? '');
    const windowMinutes = Number.parseFloat(root.dataset.windowMinutes ?? '');
    const renderMinute = Number.parseFloat(root.dataset.renderMinute ?? '');
    const renderEpoch = Number.parseInt(root.dataset.renderEpoch ?? '', 10);
    const use24 = root.dataset.clockFormat === '24';
    const timed = Number.isFinite(openMinute) && Number.isFinite(windowMinutes)
        && Number.isFinite(renderMinute) && Number.isFinite(renderEpoch) && windowMinutes > 0;

    root.setAttribute('data-dash-ready', '');

    let lastMinute = -1;
    let lastFraction = null;
    let lastPhase = null;
    let handle = 0;

    const clockAt = (epoch) => {
        const minute = renderMinute + (epoch - renderEpoch) / 60_000;

        return {
            minute,
            fraction: Math.min(1, Math.max(0, (minute - openMinute) / windowMinutes)),
            phase: minute < openMinute ? 'before' : (minute > openMinute + windowMinutes ? 'after' : 'in'),
        };
    };

    const frame = () => {
        const epoch = Date.now();

        if (timed) {
            const now = clockAt(epoch);

            if (Math.floor(now.minute) !== lastMinute) {
                lastMinute = Math.floor(now.minute);

                const text = deskClockText(now.minute, use24);
                const iso = new Date(epoch).toISOString();

                clocks.forEach((element) => {
                    element.textContent = text;
                    element.setAttribute('datetime', iso);
                });
            }

            if (state && now.phase !== lastPhase) {
                lastPhase = now.phase;
                state.textContent = state.dataset[`label${now.phase.charAt(0).toUpperCase()}${now.phase.slice(1)}`] ?? state.textContent;
            }

            /* Sub-minute precision: the day is genuinely moving, even where an eye
               cannot follow it. The line is placed by `left` off a custom property,
               which changes about once a minute and never per frame. */
            if (Math.abs(now.fraction - (lastFraction ?? -1)) > 0.00008) {
                lastFraction = now.fraction;

                const value = now.fraction.toFixed(5);
                rail?.style.setProperty('--nowf', value);
                daybar?.style.setProperty('--nowf', value);
            }
        }

        // The only thing that changes after the paint is the minute, and the minute
        // only changes once a minute. With no clock to keep there is no loop to run.
        if (! timed) {
            handle = 0;

            return;
        }

        handle = window.requestAnimationFrame(frame);
    };

    const start = () => {
        if (! handle) {
            handle = window.requestAnimationFrame(frame);
        }
    };

    const settle = (spans) => {
        spans.forEach((span, index) => {
            // Spans are laid in the order they happen, which is the order they are set.
            const delay = Math.min(index * 40, 240);

            span.style.transitionDelay = `${delay}ms`;
            span.classList.add('is-set');

            // The delay belongs to the settle only. Left in place it would also
            // hold up every hover for the rest of the visit, and feedback that
            // arrives 240ms late is not feedback.
            window.setTimeout(() => {
                span.style.transitionDelay = '';
            }, delay + 400);
        });
    };

    const show = (section) => {
        if (section.classList.contains('is-in')) {
            return;
        }

        section.classList.add('is-in');
        settle([...section.querySelectorAll('.hb-span')]);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (! entry.isIntersecting) {
                return;
            }

            show(entry.target);
            observer.unobserve(entry.target);
        });
    }, {
        root: scroller || null,
        rootMargin: '0px 0px -6% 0px',
        threshold: 0.08,
    });

    reveals.forEach((section) => observer.observe(section));

    /* The observer only fires on a change of intersection, so whatever is already
       in view when the desk arrives would wait for a scroll to appear. One pass,
       after the first paint, says otherwise. */
    window.setTimeout(() => {
        reveals.forEach((section) => {
            if (section.getBoundingClientRect().top < window.innerHeight - 24) {
                show(section);
                observer.unobserve(section);
            }
        });
    }, 80);

    start();

    return () => {
        if (handle) {
            window.cancelAnimationFrame(handle);
            handle = 0;
        }

        observer.disconnect();
        root.removeAttribute('data-dash-ready');
    };
}

let deskTeardown = null;

function deskRefresh() {
    if (typeof deskTeardown === 'function') {
        deskTeardown();
    }

    deskTeardown = bootDayDesk();
}

document.addEventListener('DOMContentLoaded', deskRefresh);
document.addEventListener('livewire:navigated', () => window.setTimeout(deskRefresh, 0));
