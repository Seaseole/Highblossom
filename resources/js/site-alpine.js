import collapse from '@alpinejs/collapse';

// Livewire 4 ships and boots the one Alpine instance the public site runs on. Importing
// alpinejs here as well started a second copy, and components registered on either one
// were invisible to the other, so these registrations are queued and applied when
// Livewire's Alpine reaches alpine:init.
const registrations = [];

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);

    registrations.forEach((register) => register());
});

const siteAlpine = {
    data: (...args) => registrations.push(() => window.Alpine.data(...args)),
};

// Full-screen viewer for content-block image sets. It reads its slides from the
// tiles already in the DOM, so blocks stay inert markup and the page mounts this
// once per post.
siteAlpine.data('cbImageLightbox', () => ({
    isOpen: false,
    slides: [],
    index: 0,
    lastFocused: null,

    init() {
        this.$watch('isOpen', (open) => {
            document.body.style.overflow = open ? 'hidden' : '';
        });
    },

    captureTileClick(event) {
        const item = event.target.closest('[data-lightbox-item]');

        if (!item) return;

        const group = item.closest('[data-lightbox-group]');

        if (!group) return;

        const tiles = Array.from(group.querySelectorAll('[data-lightbox-item]'));

        this.slides = tiles.map((tile) => {
            const img = tile.querySelector('img');
            const caption = tile.closest('figure')?.querySelector('figcaption');

            return {
                src: img?.src || '',
                alt: img?.alt || '',
                caption: caption ? caption.textContent.trim() : '',
            };
        });

        this.index = tiles.indexOf(item);
        this.lastFocused = item;
        this.isOpen = true;

        this.$nextTick(() => this.$refs.closeButton?.focus());
    },

    /**
     * Keep Tab inside the open dialog. Alpine's x-trap lives in the focus
     * plugin, which this site does not load.
     */
    trapFocus(event) {
        if (!this.isOpen) return;

        const focusables = Array.from(this.$refs.dialog.querySelectorAll('button'))
            .filter((el) => !el.disabled && getComputedStyle(el).display !== 'none');

        if (!focusables.length) return;

        const first = focusables[0];
        const last = focusables[focusables.length - 1];
        const active = document.activeElement;
        const outside = !this.$refs.dialog.contains(active);

        if (event.shiftKey && (active === first || outside)) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && (active === last || outside)) {
            event.preventDefault();
            first.focus();
        }
    },

    current() {
        return this.slides[this.index] || { src: '', alt: '', caption: '' };
    },

    next() {
        this.index = (this.index + 1) % this.slides.length;
    },

    previous() {
        this.index = (this.index - 1 + this.slides.length) % this.slides.length;
    },

    close() {
        this.isOpen = false;
        this.slides = [];
        this.index = 0;

        this.lastFocused?.focus();
        this.lastFocused = null;
    },
}));

// Blog command palette: Ctrl/Cmd+K from anywhere on the blog, predictive results,
// and search history kept in the browser only.
siteAlpine.data('blogSearchPalette', () => ({
    STORAGE_KEY: 'highblossom-blog-search',
    HISTORY_LIMIT: 6,
    MIN_QUERY: 2,
    MAX_QUERY: 80,
    DEBOUNCE_MS: 250,

    open: false,
    query: '',
    mode: 'recent',
    posts: [],
    categories: [],
    tags: [],
    history: [],
    resting: null,
    loading: false,
    failed: false,
    active: -1,
    endpoint: '',
    listing: '',
    controller: null,
    timer: null,
    request: 0,
    lastFocused: null,
    bodyOverflow: '',
    openHandler: null,
    keyHandler: null,

    init() {
        this.endpoint = this.$el.dataset.endpoint;
        this.listing = this.$el.dataset.listing;
        this.history = this.readHistory();

        // Bound directly rather than with x-on:...window: the header trigger sits in plain
        // Blade outside this component's tree. The trigger hands itself over so focus can
        // return to it even where a click does not focus the button (Safari).
        this.openHandler = (event) => {
            if (!this.open) this.show(event.detail?.trigger);
        };
        this.keyHandler = (event) => this.onGlobalKey(event);

        window.addEventListener('blog-search:open', this.openHandler);
        window.addEventListener('keydown', this.keyHandler);

        this.fetch('');

        this.$watch('open', (open) => {
            if (open) {
                this.bodyOverflow = document.body.style.overflow;
                document.body.style.overflow = 'hidden';

                return;
            }

            document.body.style.overflow = this.bodyOverflow;
        });
    },

    // window listeners outlive the element, and wire:navigate replaces the whole body,
    // so they have to come back off or every blog view stacks another Ctrl/Cmd+K handler.
    destroy() {
        if (this.openHandler) window.removeEventListener('blog-search:open', this.openHandler);
        if (this.keyHandler) window.removeEventListener('keydown', this.keyHandler);
    },

    /**
     * Fill the palette's resting state from the cache or a fresh request.
     */
    prefill() {
        if (this.resting) {
            this.apply(this.resting);
        }
    },

    onGlobalKey(event) {
        // A copy left behind by an earlier view must not answer the shortcut.
        if (!this.$el?.isConnected) return;

        const key = (event.key || '').toLowerCase();

        if ((event.ctrlKey || event.metaKey) && key === 'k') {
            event.preventDefault();
            this.toggle();

            return;
        }

        if (this.open && key === 'escape') {
            event.preventDefault();
            this.close();

            return;
        }

        // "/" opens the palette, but never over a field the reader is typing in.
        if (!this.open && event.key === '/' && !event.ctrlKey && !event.metaKey && !event.altKey && !this.isTypingTarget(event.target)) {
            event.preventDefault();
            this.show();
        }
    },

    isTypingTarget(node) {
        if (!node || !node.closest) return false;

        return Boolean(node.closest('input, textarea, select, [contenteditable="true"], [contenteditable=""]'));
    },

    toggle() {
        this.open ? this.close() : this.show();
    },

    show(trigger) {
        this.lastFocused = trigger || document.activeElement;
        this.open = true;
        this.history = this.readHistory();

        if (!this.resting) {
            this.fetch('');
        }

        this.$nextTick(() => {
            this.$refs.input?.focus();
            this.$refs.input?.select();
        });
    },

    close() {
        this.open = false;
        this.cancel();

        if (this.lastFocused?.isConnected) {
            this.lastFocused.focus();
        }

        this.lastFocused = null;
    },

    /**
     * Keep Tab inside the dialog. The Alpine focus plugin is not loaded on this site.
     */
    trapFocus(event) {
        // getClientRects, not offsetParent: offsetParent is null inside a fixed panel.
        const focusables = Array.from(
            this.$refs.dialog.querySelectorAll('input, button:not([tabindex="-1"]), a[href]')
        ).filter((el) => !el.disabled && el.getClientRects().length > 0);

        if (!focusables.length) return;

        const first = focusables[0];
        const last = focusables[focusables.length - 1];
        const active = document.activeElement;
        const outside = !this.$refs.dialog.contains(active);

        if (event.shiftKey && (active === first || outside)) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && (active === last || outside)) {
            event.preventDefault();
            first.focus();
        }
    },

    onInput() {
        this.cancel();
        this.active = -1;

        if (this.isResting()) {
            this.prefill();
            this.failed = false;

            return;
        }

        this.timer = setTimeout(() => this.fetch(this.clean(this.query)), this.DEBOUNCE_MS);
    },

    clean(term) {
        return (term || '').trim().replace(/\s+/g, ' ').slice(0, this.MAX_QUERY);
    },

    isResting() {
        return this.clean(this.query).length < this.MIN_QUERY;
    },

    cancel() {
        clearTimeout(this.timer);
        this.controller?.abort();
        this.controller = null;
    },

    /**
     * Request a payload, discarding any response a newer request has superseded.
     */
    async fetch(term) {
        this.cancel();

        const controller = new AbortController();
        const id = ++this.request;

        this.controller = controller;
        this.loading = true;
        this.failed = false;

        const url = term ? `${this.endpoint}?q=${encodeURIComponent(term)}` : this.endpoint;

        try {
            const response = await fetch(url, {
                signal: controller.signal,
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) throw new Error(`search responded ${response.status}`);

            const data = await response.json();

            if (id !== this.request) return;

            if (data.mode === 'recent') {
                this.resting = data;

                if (this.clean(this.query).length >= this.MIN_QUERY) return;
            }

            this.apply(data);
        } catch (error) {
            if (error.name === 'AbortError' || id !== this.request) return;

            this.failed = true;
            this.posts = [];
            this.categories = [];
            this.tags = [];
        } finally {
            if (id === this.request) {
                this.loading = false;
                this.controller = null;
            }
        }
    },

    apply(data) {
        this.mode = data.mode;
        this.posts = data.posts || [];
        this.categories = data.categories || [];
        this.tags = data.tags || [];
    },

    /**
     * The selectable rows, grouped for display but numbered as one listbox.
     */
    groups() {
        const saved = this.isResting()
            ? this.history.map((term) => ({ id: `saved-${term}`, type: 'saved', label: term, term, meta: 'Search again' }))
            : [];

        const buckets = [
            { type: 'saved', items: saved },
            { type: 'post', items: this.posts.map((post) => ({ ...post, term: post.label })) },
            { type: 'category', items: this.categories },
            { type: 'tag', items: this.tags },
        ];

        let index = 0;

        return buckets
            .filter((bucket) => bucket.items.length)
            .map((bucket) => ({
                type: bucket.type,
                label: this.labelFor(bucket.type),
                items: bucket.items.map((item) => ({ ...item, index: index++ })),
            }));
    },

    options() {
        return this.groups().flatMap((group) => group.items);
    },

    labelFor(type) {
        return {
            saved: 'Recent searches',
            post: this.mode === 'recent' ? 'Recent posts' : 'Articles',
            category: 'Categories',
            tag: 'Tags',
        }[type];
    },

    isActive(index) {
        return this.active === index;
    },

    activeId() {
        return this.active >= 0 ? `blog-search-option-${this.active}` : null;
    },

    statusText() {
        if (this.loading) return `Searching for ${this.query}`;

        if (this.failed) return 'Blog search is unavailable right now.';

        const count = this.options().length;

        if (this.isResting()) {
            return count ? `Showing ${count} recent suggestions.` : 'Nothing to suggest yet.';
        }

        return count ? `${count} results for ${this.clean(this.query)}.` : `No results for ${this.clean(this.query)}.`;
    },

    move(delta) {
        const count = this.options().length;

        if (!count) {
            this.active = -1;

            return;
        }

        this.active = (this.active + delta + count) % count;

        this.$nextTick(() => {
            const row = this.$refs.list?.querySelector(`#blog-search-option-${this.active}`);

            row?.scrollIntoView({ block: 'nearest', behavior: this.reduceMotion() ? 'auto' : 'smooth' });
        });
    },

    reduceMotion() {
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    },

    /**
     * Arrow, Enter, Escape and Delete handling for the combobox input.
     */
    onKey(event) {
        const key = event.key;

        if (key === 'ArrowDown') {
            event.preventDefault();
            this.move(1);

            return;
        }

        if (key === 'ArrowUp') {
            event.preventDefault();
            this.move(-1);

            return;
        }

        if (key === 'Enter') {
            event.preventDefault();
            const option = this.options()[this.active];

            option ? this.choose(option) : this.submit();

            return;
        }

        if ((key === 'Delete' || key === 'Backspace') && this.active >= 0) {
            const option = this.options()[this.active];

            if (option?.type === 'saved' && this.query.trim() === '') {
                event.preventDefault();
                this.removeHistory(option.term);
                this.active = -1;
            }
        }
    },

    choose(option) {
        if (option.type === 'saved') {
            this.query = option.term;
            this.cancel();
            this.active = -1;
            this.fetch(this.clean(option.term));

            return;
        }

        this.pushHistory(option.term);
        window.location.assign(option.href);
    },

    /**
     * Enter with nothing highlighted: hand off to the full results page, which also
     * serves readers without JavaScript.
     */
    submit() {
        const term = this.clean(this.query);

        if (!term) {
            this.close();

            return;
        }

        this.pushHistory(term);
        window.location.assign(`${this.listing}?search=${encodeURIComponent(term)}`);
    },

    readHistory() {
        try {
            const parsed = JSON.parse(window.localStorage.getItem(this.STORAGE_KEY) || '[]');

            if (!Array.isArray(parsed)) return [];

            const seen = new Set();
            const terms = [];

            for (const item of parsed) {
                const term = typeof item === 'string' ? this.clean(item) : '';
                const needle = term.toLowerCase();

                if (!term || seen.has(needle)) continue;

                seen.add(needle);
                terms.push(term);

                if (terms.length === this.HISTORY_LIMIT) break;
            }

            return terms;
        } catch {
            return [];
        }
    },

    writeHistory(terms) {
        try {
            window.localStorage.setItem(this.STORAGE_KEY, JSON.stringify(terms.slice(0, this.HISTORY_LIMIT)));
        } catch {
            // Private-mode storage rejects writes; history simply will not persist.
        }
    },

    pushHistory(value) {
        const term = this.clean(value);

        if (!term) return;

        this.history = [term, ...this.readHistory().filter((item) => item.toLowerCase() !== term.toLowerCase())];
        this.writeHistory(this.history);
    },

    removeHistory(term) {
        this.history = this.history.filter((item) => item !== term);
        this.writeHistory(this.history);
    },

    clearHistory() {
        this.history = [];

        try {
            window.localStorage.removeItem(this.STORAGE_KEY);
        } catch {
            // Nothing to clear when storage is unavailable.
        }
    },

    /**
     * Highlight the matched term by building text nodes and <mark> elements, so no
     * post content or keystroke is ever parsed as HTML.
     */
    mark(element, text, term) {
        const value = String(text ?? '');
        const needle = this.clean(term).toLowerCase();

        element.replaceChildren();

        if (!needle) {
            element.append(document.createTextNode(value));

            return;
        }

        const haystack = value.toLowerCase();
        let cursor = 0;

        while (cursor < value.length) {
            const hit = haystack.indexOf(needle, cursor);

            if (hit === -1) {
                element.append(document.createTextNode(value.slice(cursor)));

                break;
            }

            if (hit > cursor) {
                element.append(document.createTextNode(value.slice(cursor, hit)));
            }

            const highlight = document.createElement('mark');
            highlight.className = 'blog-search-hit';
            highlight.append(document.createTextNode(value.slice(hit, hit + needle.length)));
            element.append(highlight);

            cursor = hit + needle.length;
        }
    },
}));

// Public theme toggle (Light / Dark / System). All persistence and DOM application
// lives in window.highblossomTheme (inline head script, partials/theme-script); this
// component only drives the popup and re-reads the stored mode on every page so the
// displayed state stays correct across wire:navigate.
siteAlpine.data('themeToggle', () => ({
    open: false,
    mode: 'system',
    options: ['light', 'dark', 'system'],

    init() {
        this.mode = window.highblossomTheme?.get() ?? 'system';
    },

    labelFor(option) {
        return { light: 'Light', dark: 'Dark', system: 'System' }[option] ?? option;
    },

    toggle() {
        this.open = !this.open;

        if (this.open) {
            this.$nextTick(() => this.focusOption(this.mode));
        }
    },

    close() {
        this.open = false;
    },

    choose(option) {
        this.mode = option;
        window.highblossomTheme?.set(option);
        this.close();
    },

    onKeydown(event, index) {
        const step = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 }[event.key];

        if (step === undefined) {
            return;
        }

        event.preventDefault();
        this.focusOption(this.options[(index + step + this.options.length) % this.options.length]);
    },

    focusOption(option) {
        const index = this.options.indexOf(option);
        this.$el.querySelector(`[data-index="${index === -1 ? 0 : index}"]`)?.focus();
    },
}));

// The cinematic hero. Its autoplay is a CSS fill rather than a timer: when the fill finishes,
// the slide changes, so the progress bar cannot drift from the interval it is drawing, and one
// `animation-play-state` pauses both. Reduced motion removes the fill, which removes the tick —
// the carousel does not slow down, it holds still.
//
// The deck itself is read from the slides the component already printed, so there is no second
// copy of it in JavaScript. The nodes are queried rather than stored: Alpine proxies anything
// parked in its state, and a proxied DOM element is a slow way to learn what a classList is.
siteAlpine.data('heroCinematic', () => ({
    index: 0,
    count: 0,
    slides: [],
    autoplay: true,
    reduced: false,
    hovered: false,
    focused: false,
    tabHidden: false,
    live: '',
    touchFrom: null,

    init() {
        const nodes = [...this.$el.querySelectorAll('[data-hero-slide]')];

        this.count = nodes.length;
        this.slides = nodes.map((node) => ({ ...node.dataset }));
        this.autoplay = this.$el.dataset.heroAutoplay !== '0';
        this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        this.onVisibility = () => {
            this.tabHidden = document.hidden;
        };

        document.addEventListener('visibilitychange', this.onVisibility);

        this.loadRemainingSlides();
        this.watchCounters();
        this.restart();
    },

    destroy() {
        document.removeEventListener('visibilitychange', this.onVisibility);
        this.statsObserver?.disconnect();
        this.idleHandle !== undefined && window.cancelIdleCallback?.(this.idleHandle);
        this.idleTimer !== undefined && window.clearTimeout(this.idleTimer);
        this.raf !== undefined && window.cancelAnimationFrame(this.raf);
    },

    get current() {
        return this.slides[this.index] ?? {};
    },

    get next() {
        return this.slides[(this.index + 1) % Math.max(this.count, 1)] ?? {};
    },

    get paused() {
        return this.hovered || this.focused || this.tabHidden;
    },

    get running() {
        return this.autoplay && this.count > 1 && !this.reduced;
    },

    node(index) {
        return this.$el.querySelectorAll('[data-hero-slide]')[index];
    },

    go(index, announce = false) {
        if (this.count === 0) {
            return;
        }

        const target = ((index % this.count) + this.count) % this.count;

        if (target === this.index) {
            return;
        }

        this.node(this.index)?.classList.remove('is-active');
        this.node(this.index)?.setAttribute('aria-hidden', 'true');

        this.index = target;

        this.node(target)?.classList.add('is-active');
        this.node(target)?.setAttribute('aria-hidden', 'false');

        this.loadSlide(target);

        if (announce) {
            this.live = this.slides[target]?.label ?? '';
        }

        this.enterCopy();
        this.restart();
    },

    advance() {
        this.go(this.index + 1, true);
    },

    previous() {
        this.go(this.index - 1, true);
    },

    tick() {
        this.go(this.index + 1, false);
    },

    swipe(event) {
        if (this.touchFrom === null) {
            return;
        }

        const delta = event.clientX - this.touchFrom;

        this.touchFrom = null;

        if (Math.abs(delta) < 48) {
            return;
        }

        delta < 0 ? this.advance() : this.previous();
    },

    // Reflow-gated restart: removing the class and adding it back in the same frame is read as
    // no change at all, which is why the reflow has to be forced between the two.
    restart() {
        const bar = this.$refs.bar;

        if (!bar || !this.running) {
            return;
        }

        bar.classList.remove('is-running');
        void bar.offsetWidth;
        bar.classList.add('is-running');
    },

    enterCopy() {
        const copy = this.$refs.copy;

        if (!copy || this.reduced) {
            return;
        }

        copy.classList.remove('is-entering');
        void copy.offsetWidth;
        copy.classList.add('is-entering');
    },

    loadRemainingSlides() {
        const task = () => {
            for (let index = 1; index < this.count; index++) {
                this.loadSlide(index);
            }
        };

        // The first slide is the largest paint, so everything else waits until the page is
        // quiet. Idle rather than immediate, and with a timeout, so a busy main thread still
        // gets the next frame loaded long before the seven-second interval asks for it.
        if (typeof window.requestIdleCallback === 'function') {
            this.idleHandle = window.requestIdleCallback(task, { timeout: 2000 });

            return;
        }

        this.idleTimer = window.setTimeout(task, 400);
    },

    loadSlide(index) {
        this.node(index)?.querySelectorAll('[data-hero-src]').forEach((image) => {
            image.setAttribute('src', image.dataset.heroSrc);

            if (image.dataset.heroSrcset) {
                image.setAttribute('srcset', image.dataset.heroSrcset);
            }

            delete image.dataset.heroSrc;
            delete image.dataset.heroSrcset;
        });
    },

    watchCounters() {
        const list = this.$el.querySelector('[data-hero-stats]');

        if (!list) {
            return;
        }

        const counters = [...list.querySelectorAll('[data-count-to]')].map((element) => ({
            element,
            to: Number.parseFloat(element.dataset.countTo),
            decimals: Number.parseInt(element.dataset.decimals ?? '0', 10),
            grouped: element.dataset.grouped === '1',
        }));

        if (counters.length === 0 || this.reduced || typeof IntersectionObserver !== 'function') {
            // Reduced motion and no observer both mean the same thing here: the number an admin
            // typed is already in the HTML, and it is not going to move.
            return;
        }

        this.statsObserver = new IntersectionObserver((entries) => {
            if (!entries.some((entry) => entry.isIntersecting)) {
                return;
            }

            this.statsObserver.disconnect();
            this.countUp(counters);
        }, { threshold: 0.25 });

        this.statsObserver.observe(list);
    },

    countUp(counters) {
        const duration = 1400;
        const started = performance.now();

        const frame = (now) => {
            const progress = Math.min(1, (now - started) / duration);
            const eased = 1 - (1 - progress) ** 3;

            counters.forEach((counter) => {
                counter.element.textContent = this.format(counter, counter.to * eased);
            });

            if (progress < 1) {
                this.raf = window.requestAnimationFrame(frame);
            }
        };

        counters.forEach((counter) => {
            counter.element.textContent = this.format(counter, 0);
        });

        this.raf = window.requestAnimationFrame(frame);
    },

    format(counter, value) {
        const text = value.toFixed(counter.decimals);

        if (!counter.grouped) {
            return text;
        }

        const [whole, fraction] = text.split('.');

        return whole.replace(/\B(?=(\d{3})+(?!\d))/g, ',') + (fraction ? `.${fraction}` : '');
    },

    padded(value) {
        return String(value).padStart(2, '0');
    },
}));
