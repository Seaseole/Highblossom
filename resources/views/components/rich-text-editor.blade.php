@props([
    'content' => '',
    'name' => 'content',
    'placeholder' => 'Start typing...',
    'height' => '150px',
])

<div 
    x-data="richTextEditor(@js($content), @js($name))"
    x-init="init()"
    class="relative w-full"
>
    <!-- Toolbar -->
    <div class="flex items-center gap-1 p-2 mb-2 rounded-t-xl bg-white/5 border border-white/10 border-b-0">
        <!-- Text Formatting -->
        <div class="flex items-center gap-1 pr-2 border-r border-white/10">
            <button 
                @click="formatText('bold')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isActive('bold'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isActive('bold') }"
                class="p-2 rounded-lg transition-all duration-200"
                title="Bold (Ctrl+B)"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z" />
                </svg>
            </button>
            <button 
                @click="formatText('italic')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isActive('italic'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isActive('italic') }"
                class="p-2 rounded-lg transition-all duration-200"
                title="Italic (Ctrl+I)"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h4m-2 0v16m-4 0h8" />
                </svg>
            </button>
            <button 
                @click="formatText('underline')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isActive('underline'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isActive('underline') }"
                class="p-2 rounded-lg transition-all duration-200"
                title="Underline (Ctrl+U)"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v7a5 5 0 0010 0V4M5 20h14" />
                </svg>
            </button>
            <button 
                @click="formatText('strikeThrough')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isActive('strikeThrough'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isActive('strikeThrough') }"
                class="p-2 rounded-lg transition-all duration-200"
                title="Strikethrough"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 10H7m10 4H7m5-9v12" />
                </svg>
            </button>
        </div>

        <!-- Headings -->
        <div class="flex items-center gap-1 px-2 border-r border-white/10">
            <button 
                @click="formatBlock('formatBlock', 'h2')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isBlockActive('h2'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isBlockActive('h2') }"
                class="p-2 rounded-lg transition-all duration-200 font-bold text-sm"
                title="Heading 2"
            >
                H2
            </button>
            <button 
                @click="formatBlock('formatBlock', 'h3')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isBlockActive('h3'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isBlockActive('h3') }"
                class="p-2 rounded-lg transition-all duration-200 font-bold text-sm"
                title="Heading 3"
            >
                H3
            </button>
        </div>

        <!-- Lists -->
        <div class="flex items-center gap-1 px-2 border-r border-white/10">
            <button 
                @click="formatText('insertUnorderedList')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isActive('insertUnorderedList'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isActive('insertUnorderedList') }"
                class="p-2 rounded-lg transition-all duration-200"
                title="Bullet List"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <button 
                @click="formatText('insertOrderedList')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isActive('insertOrderedList'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isActive('insertOrderedList') }"
                class="p-2 rounded-lg transition-all duration-200"
                title="Numbered List"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                </svg>
            </button>
        </div>

        <!-- Alignment -->
        <div class="flex items-center gap-1 px-2 border-r border-white/10">
            <button 
                @click="formatBlock('justifyLeft')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isActive('justifyLeft'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isActive('justifyLeft') }"
                class="p-2 rounded-lg transition-all duration-200"
                title="Align Left"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16" />
                </svg>
            </button>
            <button 
                @click="formatBlock('justifyCenter')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isActive('justifyCenter'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isActive('justifyCenter') }"
                class="p-2 rounded-lg transition-all duration-200"
                title="Align Center"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16" />
                </svg>
            </button>
            <button 
                @click="formatBlock('justifyRight')"
                :class="{ 'bg-[#DC2626]/20 text-[#DC2626]': isActive('justifyRight'), 'text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10': !isActive('justifyRight') }"
                class="p-2 rounded-lg transition-all duration-200"
                title="Align Right"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Links -->
        <div class="flex items-center gap-1 pl-2">
            <button 
                @click="insertLink()"
                class="p-2 rounded-lg text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10 transition-all duration-200"
                title="Insert Link"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </button>
            <button 
                @click="removeLink()"
                class="p-2 rounded-lg text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10 transition-all duration-200"
                title="Remove Link"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </button>
        </div>

        <!-- Clear Formatting -->
        <div class="flex items-center gap-1 pl-2">
            <button 
                @click="formatText('removeFormat')"
                class="p-2 rounded-lg text-[#A1A1AA] hover:text-[#FAFAFA] hover:bg-white/10 transition-all duration-200"
                title="Clear Formatting"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Editor -->
    <div 
        x-ref="editor"
        contenteditable="true"
        x-html="sanitizedContent"
        @input="handleInput($event)"
        @keydown="handleKeydown($event)"
        @blur="handleBlur()"
        class="w-full min-h-[{{ $height }}] px-4 py-3 rounded-b-xl bg-white/5 border border-white/10 text-[#FAFAFA] focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:border-transparent transition-all duration-200 prose prose-invert prose-sm max-w-none [&_a]:text-[#DC2626] [&_a]:hover:underline [&_strong]:text-[#FAFAFA] [&_em]:text-[#A1A1AA]"
        data-placeholder="{{ $placeholder }}"
    ></div>

    <!-- Character Count -->
    <div class="flex items-center justify-between mt-2 text-xs text-[#71717A]">
        <span>
            <span x-text="charCount"></span> characters
        </span>
        <span x-show="wordCount > 0">
            <span x-text="wordCount"></span> words
        </span>
    </div>
</div>

@script
<script>
function richTextEditor(initialContent, fieldName) {
    return {
        content: initialContent,
        fieldName: fieldName,
        sanitizedContent: this.sanitizeHtml(initialContent),
        
        init() {
            this.$watch('content', (value) => {
                this.sanitizedContent = this.sanitizeHtml(value);
            });
            
            // Listen for link insertion from parent
            this.$listen('insert-link', (event) => {
                this.insertLink(event.url);
            });
        },
        
        handleInput(event) {
            this.content = event.target.innerHTML;
            this.$dispatch('update-rich-text', { 
                field: this.fieldName, 
                content: this.content 
            });
        },
        
        handleKeydown(event) {
            // Handle keyboard shortcuts
            if (event.ctrlKey || event.metaKey) {
                switch(event.key.toLowerCase()) {
                    case 'b':
                        event.preventDefault();
                        this.formatText('bold');
                        break;
                    case 'i':
                        event.preventDefault();
                        this.formatText('italic');
                        break;
                    case 'u':
                        event.preventDefault();
                        this.formatText('underline');
                        break;
                }
            }
            
            // Handle Enter key to create paragraphs instead of divs
            if (event.key === 'Enter') {
                event.preventDefault();
                document.execCommand('insertParagraph', false, null);
            }
        },
        
        handleBlur() {
            this.$dispatch('blur-rich-text', { 
                field: this.fieldName, 
                content: this.content 
            });
        },
        
        formatText(command, value = null) {
            document.execCommand(command, false, value);
            this.content = this.$refs.editor.innerHTML;
            this.$dispatch('update-rich-text', { 
                field: this.fieldName, 
                content: this.content 
            });
        },
        
        formatBlock(command, value = null) {
            document.execCommand(command, false, value);
            this.content = this.$refs.editor.innerHTML;
            this.$dispatch('update-rich-text', { 
                field: this.fieldName, 
                content: this.content 
            });
        },
        
        insertLink(url = null) {
            if (!url) {
                url = prompt('Enter URL:', 'https://');
            }
            
            if (url) {
                document.execCommand('createLink', false, url);
                this.content = this.$refs.editor.innerHTML;
                this.$dispatch('update-rich-text', { 
                    field: this.fieldName, 
                    content: this.content 
                });
            }
        },
        
        removeLink() {
            document.execCommand('unlink', false, null);
            this.content = this.$refs.editor.innerHTML;
            this.$dispatch('update-rich-text', { 
                field: this.fieldName, 
                content: this.content 
            });
        },
        
        isActive(command) {
            return document.queryCommandState(command);
        },
        
        isBlockActive(tag) {
            const blockElements = this.$refs.editor.querySelectorAll(tag);
            return Array.from(blockElements).some(el => 
                el === document.activeElement || el.contains(document.activeElement)
            );
        },
        
        sanitizeHtml(html) {
            if (!html) return '';
            
            // Basic sanitization - remove script tags and dangerous attributes
            const temp = document.createElement('div');
            temp.innerHTML = html;
            
            // Remove script tags
            const scripts = temp.querySelectorAll('script');
            scripts.forEach(script => script.remove());
            
            // Remove on* attributes
            const allElements = temp.querySelectorAll('*');
            allElements.forEach(el => {
                Array.from(el.attributes).forEach(attr => {
                    if (attr.name.startsWith('on')) {
                        el.removeAttribute(attr.name);
                    }
                });
            });
            
            return temp.innerHTML;
        },
        
        get charCount() {
            return this.content.replace(/<[^>]*>/g, '').length;
        },
        
        get wordCount() {
            const text = this.content.replace(/<[^>]*>/g, '').trim();
            return text ? text.split(/\s+/).length : 0;
        }
    }
}
</script>
@endscript

<style>
[data-placeholder]:empty:before {
    content: attr(data-placeholder);
    color: #71717A;
    pointer-events: none;
}
</style>
