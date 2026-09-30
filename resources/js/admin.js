import collapse from '@alpinejs/collapse';
import { Passkeys } from '@laravel/passkeys';

console.log('Admin JS: Script loaded');

// Initialize Passkeys
window.Passkeys = Passkeys;

// Register plugins and components when Alpine is ready
document.addEventListener('alpine:init', () => {
    console.log('Admin JS: alpine:init fired');

    // Register plugins
    window.Alpine.plugin(collapse);

    // Types whose editors host nested block lists; they cannot be added inside a nested list.
    const NESTING_TYPES = ['columns', 'tabs', 'carousel'];

    // Media library picker used by <x-media-picker /> in the admin layout.
    window.Alpine.data('mediaPicker', () => ({
        field: null,
        isOpen: false,
        images: [],
        loading: false,
        selectedImage: null,

        async loadImages() {
            this.loading = true;
            try {
                const response = await fetch('/admin/media-library', {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await response.json();
                this.images = data.images || [];
            } catch (error) {
                console.error('Failed to load images:', error);
            } finally {
                this.loading = false;
            }
        },

        async uploadImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('upload', file);
            formData.append('title', file.name.replace(/\.[^.]+$/, ''));
            formData.append('category', 'other');

            this.loading = true;
            try {
                await fetch('/admin/media-library/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                await this.loadImages();
            } catch (error) {
                console.error('Failed to upload image:', error);
            } finally {
                this.loading = false;
                event.target.value = '';
            }
        },

        selectImage(image) {
            this.selectedImage = image;
        },

        confirmSelection() {
            if (this.selectedImage && this.field) {
                this.$dispatch('image-selected', {
                    field: this.field,
                    url: this.selectedImage.url,
                });
                this.isOpen = false;
                this.selectedImage = null;
            }
        },
    }));

    window.Alpine.data('blockBuilder', (config) => ({
        blocks: Array.isArray(config.initialBlocks) ? config.initialBlocks : [],
        blockMeta: config.blockMeta || {},
        blockErrors: config.blockErrors || {},
        fieldName: config.name || 'content',
        collapsed: [],
        lastRemoved: null,
        dragIndex: null,
        dropIndex: null,
        previewOpen: false,
        previewHtml: '',
        previewLoading: false,

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

                if (block) this.setAttributePath(block, detail.field.slice(sep + 1), detail.url);
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
         * Ask the media library picker to fill a block attribute.
         */
        openPicker(block, path) {
            window.dispatchEvent(new CustomEvent('open-media-picker', { detail: { field: block.id + ':' + path } }));
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
