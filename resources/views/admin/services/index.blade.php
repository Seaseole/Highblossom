<x-layouts::admin title="{{ __('admin-services.title') }}">
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">
                    {{ __('admin-services.title') }}
                </h1>
                <p class="text-admin-text-muted text-sm max-w-lg">
                    Manage the architectural glass and aluminum services showcased on the platform.
                </p>
            </div>
            
            <a href="{{ route('admin.services.create') }}" class="admin-action-btn admin-action-btn-primary group">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-90 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>{{ __('admin-services.create') }}</span>
            </a>
        </div>

        <div class="admin-table admin-glass-card shadow-2xl shadow-black/20">
            <table class="min-w-full divide-y divide-admin-border-subtle">
                <thead class="bg-admin-accent/5">
                    <tr>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">{{ __('admin-services.image') }}</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">{{ __('admin-services.title_header') }}</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">{{ __('admin-services.icon') }}</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">{{ __('admin-services.status') }}</th>
                        <th scope="col" class="px-8 py-5 text-right text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">{{ __('admin-services.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @forelse($services as $service)
                        <tr class="group hover:bg-admin-accent/5 transition-all duration-300 ease-out-expo">
                            <td class="px-8 py-6 whitespace-nowrap">
                                @if ($service->image)
                                    <div class="relative w-16 h-16 group/img">
                                        <img src="{{ $service->image }}" alt="{{ $service->title }}" class="w-full h-full object-cover rounded-xl border border-admin-border-subtle grayscale group-hover/img:grayscale-0 transition-all duration-500">
                                        <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-white/10"></div>
                                    </div>
                                @else
                                    <div class="w-16 h-16 bg-admin-surface-alt rounded-xl border border-admin-border-subtle flex items-center justify-center">
                                        <svg class="w-8 h-8 text-admin-text-muted/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <span class="text-base font-bold text-admin-text font-headline tracking-tight">{{ $service->title }}</span>
                                    <span class="text-xs text-admin-text-muted font-body leading-relaxed max-w-xs truncate">{{ $service->short_description }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="inline-flex items-center justify-center p-2 rounded-lg bg-admin-surface-alt border border-admin-border-subtle">
                                    <span class="text-sm font-mono text-admin-text-muted lowercase tracking-tighter">
                                        {{ $service->icon ?? 'no_icon' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                @if($service->is_active)
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                                        <span class="text-[10px] font-bold text-green-500 uppercase tracking-widest font-body">
                                            {{ __('admin-services.active_status') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 opacity-50">
                                        <span class="w-2 h-2 rounded-full bg-admin-text-muted"></span>
                                        <span class="text-[10px] font-bold text-admin-text-muted uppercase tracking-widest font-body">
                                            {{ __('admin-services.inactive_status') }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <a href="{{ route('admin.services.edit', $service) }}" class="admin-action-btn admin-action-btn-ghost group/edit">
                                    <svg class="w-4 h-4 transition-colors group-hover/edit:text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="font-bold tracking-tight">{{ __('admin-services.edit_button') }}</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-20 h-20 rounded-full bg-admin-surface-alt flex items-center justify-center border border-admin-border-subtle">
                                        <svg class="w-10 h-10 text-admin-text-muted/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-admin-text-muted font-body text-sm">{{ __('admin-services.no_services_found') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($services->hasPages())
            <div class="pt-4">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</x-layouts::admin>
