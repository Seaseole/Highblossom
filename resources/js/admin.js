import collapse from '@alpinejs/collapse';
import { Passkeys } from '@laravel/passkeys';
import './password-rules';

console.log('Admin JS: Script loaded');

// Initialize Passkeys
window.Passkeys = Passkeys;

/**
 * Suspend or restore the admin content scroller.
 *
 * The layout row is viewport-tall, so content moves inside .admin-main instead of the
 * window. Overlays used to lock <body>, which after that change is an element that never
 * scrolls — the lock would be silently inert and the page would run behind the modal.
 */
function lockAdminContentScroll(locked) {
    document.querySelector('.admin-main')?.classList.toggle('is-scroll-locked', locked);
}

// Register plugins and components when Alpine is ready
document.addEventListener('alpine:init', () => {
    console.log('Admin JS: alpine:init fired');

    // Register plugins
    window.Alpine.plugin(collapse);

    // The mobile navigation drawer. The cycle is written by hand because
    // @alpinejs/focus is not installed, so x-trap would fail silently and Tab would walk
    // out of the drawer into the page behind the overlay. Elements hidden by a collapsed
    // group are filtered out, otherwise the first/last pair brackets invisible links.
    window.Alpine.data('adminDrawer', () => ({
        trap(event) {
            if (event.key !== 'Tab') {
                return;
            }

            const focusables = Array.from(
                this.$el.querySelectorAll(
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
        },
    }));

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
    const state = Alpine.reactive({
        theme: (window.__ADMIN_NAV__ && window.__ADMIN_NAV__.theme) || "auto",
        path: window.location.pathname,
        lit: (window.__ADMIN_NAV__ && window.__ADMIN_NAV__.active) || window.location.pathname,
        query: new URLSearchParams(window.location.search).get('nav_search') || '',
    });

    const strip = (subject) => String(subject ?? '/').replace(/\/+$/, '');

    /* Config arrives from window.initAdminNav(), called by the rail's boot script. Either side
       may get there first: Alpine's init fires before an inline body script on a fresh load,
       and after one on a subsequent visit. So both write to the same object and both check
       whether the other has arrived. It lives on window rather than in the DOM because a body
       swap replaces every element that is not persisted, including a script tag of JSON that
       was never marked to survive. */
    window.__ADMIN_NAV__ = window.__ADMIN_NAV__ || { path: null, active: null, theme: 'auto', themeUrl: '', text: {}, badges: {} };

    const cfg = window.__ADMIN_NAV__;

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
        get query() {
            return state.query;
        },

        set query(value) {
            state.query = value || '';
        },

        get searching() {
            return state.query.trim() !== '';
        },

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

        /* Every word has to land, on any field, in any order — so "gallery category" finds
           Gallery Categories while "settings cache" finds nothing. The group name is in the
           haystack, which is why typing "blog" reveals the whole branch. */
        matches(href) {
            if (! this.searching) {
                return true;
            }

            const text = navText()[href] || '';

            return termsOf(state.query).every((term) => text.includes(term));
        },

        /* Escaped first, tags added afterwards, so there is no moment in which a label — or the
           query, which is inside the pattern — could be read as markup. */
        highlightAll(root) {
            const terms = termsOf(state.query).sort((a, b) => b.length - a.length);
            const pattern = terms.map((term) => term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')).join('|');

            root.querySelectorAll('[data-nav-label]').forEach((link) => {
                const slot = link.querySelector('[data-nav-text]');
                const label = link.getAttribute('data-nav-label') || '';

                if (! slot) {
                    return;
                }

                if (pattern === '') {
                    slot.textContent = label;

                    return;
                }

                slot.innerHTML = escapeHtml(label).replace(
                    new RegExp(`(${pattern})`, 'gi'),
                    '<mark class="admin-nav-mark">$1</mark>',
                );
            });
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

            document.querySelectorAll('[data-admin-theme-chip]').forEach((chip) => {
                chip.setAttribute('data-admin-theme-chip', theme);
            });

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

        /* A branch stays visible while any row in it answers the query, and comes back whole
           the moment the field is cleared — the rows themselves are only hidden, never
           removed, so nothing has to be rebuilt. Reading store.searching is what subscribes
           this expression: it is a getter over the reactive query, whereas matches() would be
           an opaque call Alpine has no reason to re-run. */
        anyMatches() {
            const store = Alpine.store('adminNav');

            if (! store.searching) {
                return true;
            }

            return Array.from(this.$el.querySelectorAll('[data-nav-href]')).some((link) =>
                store.matches(link.getAttribute('data-nav-href')),
            );
        },

        /* Typing in search has to reveal every match even inside a branch the user shut, and
           hide them again the moment the query clears without overwriting what was
           remembered — which is why this reads the search flag instead of writing state. */
        isOpen() {
            return Alpine.store('adminNav').searching ? true : this.open;
        },
    }));

    /* The column that holds the groups. One root per column — the drawer and the rail each
       have their own — and both read the same store, so a query typed in one filters the other
       as well, which matters because they are never both on screen but are both mounted. */
    window.Alpine.data('adminNavRoot', () => ({
        /* Exposed as a getter so the "no matches" line and the group rows all read the same
           reactive answer, and so matches() — which is a call, not a property — is never the
           only thing standing between a keystroke and a re-evaluation. */
        get searching() {
            return Alpine.store('adminNav').searching;
        },

        get query() {
            return Alpine.store('adminNav').query;
        },

        anyMatch() {
            const store = Alpine.store('adminNav');

            if (! store.searching) {
                return true;
            }

            return Array.from(this.$el.querySelectorAll('[data-nav-href]')).some((link) =>
                store.matches(link.getAttribute('data-nav-href')),
            );
        },

        /* Highlighting is a DOM write, not a binding: Alpine cannot express "replace this
           text node with marked-up text" without re-parsing the row on every keystroke. So the
           column asks the store to rewrite the label slots once per query change, and the
           untouched text node comes back the moment the query does. */
        sync() {
            this.$watch(
                () => Alpine.store('adminNav').query,
                () => Alpine.store('adminNav').highlightAll(this.$el),
            );

            Alpine.store('adminNav').highlightAll(this.$el);
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
    const focusSearch = (event) => {
        const field = Array.from(document.querySelectorAll('.admin-nav-search-field'))
            .find((candidate) => candidate.offsetParent !== null);

        if (! field) {
            return;
        }

        event.preventDefault();
        field.focus();
        field.select();
    };

    // Cmd+K on a Mac, Ctrl+K everywhere else — picked over a bare "/" as the primary because
    // it is the muscle memory people already carry for "search this app". Nothing in the
    // panel binds it today, and it stays unbound in the content area.
    window.addEventListener('keydown', (event) => {
        if ((event.metaKey || event.ctrlKey) && String(event.key).toLowerCase() === 'k') {
            focusSearch(event);
        }
    });

    // "/" is the weaker of the two and must stay out of the way of whoever is typing, so it
    // only reaches for the rail when focus is not already inside a field. That also leaves
    // Firefox quick-find working on any page where the sidebar search is not the target.
    window.addEventListener('keydown', (event) => {
        if (event.key !== '/' || event.metaKey || event.ctrlKey || event.altKey) {
            return;
        }

        const active = document.activeElement;
        const typing = ['INPUT', 'TEXTAREA', 'SELECT'].includes(active?.tagName || '')
            || active?.isContentEditable === true;

        if (! typing) {
            focusSearch(event);
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
