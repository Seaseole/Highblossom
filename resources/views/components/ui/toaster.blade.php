<div x-data="{
    toasts: [],
    addToast(type, message, duration = 5000) {
        const id = Date.now() + Math.random().toString(36).substr(2, 9);
        // Unshift to put newest toasts at the top
        this.toasts.unshift({ id, type, message, progress: 100, paused: false, duration });
        
        // Individual auto-dismiss based on variable duration
        setTimeout(() => {
            this.dismissToast(id);
        }, duration);
    },
    dismissToast(id) {
        const index = this.toasts.findIndex(t => t.id === id);
        if (index !== -1) {
            this.toasts.splice(index, 1);
        }
    },
    init() {
        @if(session('success'))
            this.addToast('success', @js(session('success')));
        @endif
        @if(session('error'))
            this.addToast('error', @js(session('error')));
        @endif
        @if(session('info'))
            this.addToast('info', @js(session('info')));
        @endif
        @if(session('warning'))
            this.addToast('warning', @js(session('warning')));
        @endif
        @if($errors->any())
            @foreach($errors->all() as $error)
                this.addToast('error', @js($error));
            @endforeach
        @endif
    }
}" 
@toast.window="addToast($event.detail.type, $event.detail.message, $event.detail.duration || 5000)"
class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none w-full max-w-sm">
    <template x-for="toast in toasts" :key="toast.id">
        <div 
            x-show="true"
            x-transition:enter="transition duration-400"
            x-transition:enter-start="translate-x-8 scale-95 opacity-0"
            x-transition:enter-end="translate-x-0 scale-100 opacity-100"
            x-transition:leave="transition duration-300"
            x-transition:leave-start="translate-x-0 scale-100 opacity-100"
            x-transition:leave-end="translate-x-4 scale-95 opacity-0"
            style="transition-timing-function: cubic-bezier(0.23, 1, 0.32, 1);"
            class="pointer-events-auto relative overflow-hidden rounded-xl backdrop-blur-xl border shadow-2xl bg-white/90 dark:bg-[#16161D]/90"
            :class="{
                'border-emerald-500/30 dark:border-emerald-500/20': toast.type === 'success',
                'border-red-500/30 dark:border-red-500/20': toast.type === 'error',
                'border-blue-500/30 dark:border-blue-500/20': toast.type === 'info',
                'border-yellow-500/30 dark:border-yellow-500/20': toast.type === 'warning'
            }"
            @mouseenter="toast.paused = true" 
            @mouseleave="toast.paused = false"
        >
            <div class="flex items-start gap-3 p-4">
                {{-- Icon --}}
                <div class="flex-shrink-0 mt-0.5"
                    :class="{
                        'text-emerald-600 dark:text-emerald-400': toast.type === 'success',
                        'text-red-600 dark:text-red-400': toast.type === 'error',
                        'text-blue-600 dark:text-blue-400': toast.type === 'info',
                        'text-yellow-600 dark:text-yellow-400': toast.type === 'warning'
                    }">
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </template>
                </div>
                {{-- Message --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium"
                        :class="{
                            'text-emerald-800 dark:text-emerald-400': toast.type === 'success',
                            'text-red-800 dark:text-red-400': toast.type === 'error',
                            'text-blue-800 dark:text-blue-400': toast.type === 'info',
                            'text-yellow-800 dark:text-yellow-400': toast.type === 'warning'
                        }" x-text="toast.message"></p>
                </div>
                {{-- Close Button --}}
                <button @click="dismissToast(toast.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            {{-- Progress Bar --}}
            <div class="h-1 bg-black/5 dark:bg-white/5">
                <div class="h-full transition-all duration-100 ease-linear"
                    :class="{
                        'bg-emerald-500': toast.type === 'success',
                        'bg-red-500': toast.type === 'error',
                        'bg-blue-500': toast.type === 'info',
                        'bg-yellow-500': toast.type === 'warning'
                    }"
                    :style="`width: ${toast.progress}%`"
                    x-init="
                        let interval = setInterval(() => {
                            if (!toast.paused) {
                                toast.progress = Math.max(0, toast.progress - (100 / (toast.duration / 10)));
                                if (toast.progress <= 0) clearInterval(interval);
                            }
                        }, 10);
                    "
                ></div>
            </div>
        </div>
    </template>
</div>
