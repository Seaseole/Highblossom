import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
Alpine.plugin(collapse);

// Full-screen viewer for content-block image sets. It reads its slides from the
// tiles already in the DOM, so blocks stay inert markup and the page mounts this
// once per post.
Alpine.data('cbImageLightbox', () => ({
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

Alpine.start();
