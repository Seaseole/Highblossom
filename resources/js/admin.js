import collapse from '@alpinejs/collapse';
import { Passkeys } from '@laravel/passkeys';

console.log('Admin JS: Script loaded');

// Initialize Passkeys
window.Passkeys = Passkeys;

// Register plugins and components when Alpine is ready
document.addEventListener('alpine:init', () => {
    console.log('Admin JS: alpine:init fired');
    
    // Register global store for mobile menu
    window.Alpine.store('mobileMenu', {
        open: false,
        toggle() { this.open = !this.open },
        close() { this.open = false }
    });

    // Register plugins
    window.Alpine.plugin(collapse);

    // Register the blockBuilder component here to ensure it's available globally
    window.Alpine.data('blockBuilder', (config) => ({
        blocks: Array.isArray(config.initialBlocks) ? config.initialBlocks : [],
        availableBlockTypes: config.availableBlockTypes || {},

        init() {
            console.log('BlockBuilder Alpine Init', {
                blocksCount: this.blocks.length,
                typesCount: Object.keys(this.availableBlockTypes).length
            });

            this.blocks.forEach(b => {
                if (b.type === 'list' && b.attributes && Array.isArray(b.attributes.items)) {
                    b.attributes.itemsRaw = b.attributes.items.join('\n');
                }
            });

            this.$watch('blocks', () => this.sync(), { deep: true });
            this.sync();

            window.addEventListener('image-uploaded', (e) => {
                const detail = e.detail[0] || e.detail;
                if (this.blocks[detail.index]) {
                    this.blocks[detail.index].attributes.src = detail.url;
                }
            });

            window.addEventListener('video-uploaded', (e) => {
                const detail = e.detail[0] || e.detail;
                if (this.blocks[detail.index]) {
                    this.blocks[detail.index].attributes.src = detail.url;
                    if (detail.poster) this.blocks[detail.index].attributes.poster = detail.poster;
                }
            });
        },

        addBlock(type) {
            console.log('Adding block:', type);
            this.blocks.push({
                id: 'block_' + Math.random().toString(36).substr(2, 9),
                type: type,
                attributes: this.getDefaultAttributes(type)
            });
        },

        removeBlock(index) {
            this.blocks.splice(index, 1);
        },

        sync() {
            const input = document.getElementById('content-input');
            if (input) input.value = JSON.stringify(this.blocks);
        },

        getDefaultAttributes(type) {
            const d = {
                'paragraph':  { content: '', class: '' },
                'heading':    { content: '', level: 'h2', class: '' },
                'image':      { src: '', alt: '', caption: '' },
                'quote':      { content: '', author: '', cite: '', class: '' },
                'code':       { content: '', class: '' },
                'list':       { items: [], type: 'ul', class: '', itemsRaw: '' },
                'cta':        { title: '', description: '', button_text: '', button_url: '', class: '' },
                'video':      { src: '', poster: '', type: 'video/mp4', controls: true, class: '' },
                'divider':    { style: 'line', size: 'md' },
                'alert':      { type: 'info', title: '', content: '', dismissible: false },
                'html':       { content: '' },
                'embed':      { url: '', title: '' },
                'accordion':  { items: [{ title: '', content: '' }], multiple_open: false },
                'table':      { headers: ['Column 1'], rows: [['Row 1']], caption: '' },
                'gallery':    { images: [], columns: 3 },
                'form':       { fields: [], submit_text: 'Submit', action_url: '' },
                'columns':    { columns: [[], []], column_widths: [6, 6] },
                'tabs':       { tabs: [{ label: 'Tab 1', content: [] }] },
                'carousel':   { slides: [], autoplay: false, interval: 5 },
                'countdown':  { target_date: new Date().toISOString().slice(0, 16), label: '' },
                'poll':       { poll_id: null, question: '', options: ['Option 1'], allow_multiple: false, show_results: false }
            };
            return d[type] || {};
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
    password += uppercase[Math.floor(Math.random() * uppercase.length)];
    password += lowercase[Math.floor(Math.random() * lowercase.length)];
    password += numbers[Math.floor(Math.random() * numbers.length)];
    password += symbols[Math.floor(Math.random() * symbols.length)];

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
