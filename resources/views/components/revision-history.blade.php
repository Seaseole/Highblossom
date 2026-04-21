@props([
    'contentModel' => null,
])

@php
$revisions = $contentModel && method_exists($contentModel, 'revisions') ? $contentModel->revisions()->latest()->get() : collect();
@endphp

<div class="space-y-4">
    @if($revisions->isEmpty())
        <div class="text-center py-8">
            <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-sm font-medium text-[#FAFAFA] mb-1">No revision history</h3>
            <p class="text-xs text-[#A1A1AA]">Revisions will appear here as you make changes</p>
        </div>
    @else
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @foreach($revisions as $revision)
                <div class="p-4 rounded-xl bg-white/5 border border-white/10 hover:border-white/20 transition-all duration-200">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-full bg-[#DC2626]/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-[#FAFAFA]">Revision #{{ $revision->id }}</h4>
                                    <p class="text-xs text-[#71717A]">{{ $revision->created_at->format('M j, Y - g:i A') }}</p>
                                </div>
                            </div>
                            @if($revision->changes)
                                <div class="text-xs text-[#A1A1AA]">
                                    <p class="font-medium mb-1">Changes:</p>
                                    <p>{{ $revision->changes }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button 
                                wire:click="$parent.previewRevision({{ $revision->id }})"
                                class="p-2 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 transition-colors"
                                title="Preview"
                            >
                                <svg class="w-4 h-4 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <button 
                                wire:click="$parent.restoreRevision({{ $revision->id }})"
                                class="p-2 rounded-lg bg-white/5 border border-white/10 hover:bg-[#DC2626]/20 hover:text-[#DC2626] transition-colors"
                                title="Restore"
                            >
                                <svg class="w-4 h-4 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
