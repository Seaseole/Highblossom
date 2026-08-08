<div class="rounded-xl border border-border bg-card text-card-foreground shadow-xs overflow-hidden">
    <div class="px-5 py-4 border-b border-border flex items-start gap-3">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-warning/10 text-warning ring-1 ring-inset ring-warning/20">
            <i data-lucide="siren" class="text-[16px]"></i>
        </span>
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <h2 class="text-base font-semibold tracking-tight">Alert rules</h2>
                <?php echo $__env->make('jobs-monitor::settings.partials.tooltip', [
                    'text' => 'Shipped rules decide when an alert fires. Toggle off to silence a rule without deleting it; click Edit to tune threshold, window, cooldown or channels. Reset returns the row to the shipped default.',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <p class="mt-1 text-sm text-muted-foreground">
                Built-in rules that ship with the package. Disable silences the rule on the next scheduler tick (~1 minute). Reset discards overrides and returns to the shipped default.
            </p>
        </div>
    </div>

    <table class="w-full text-sm table-fixed">
        <colgroup>
            <col>
            <col class="hidden md:table-column w-[150px]">
            <col class="hidden lg:table-column w-[110px]">
            <col class="hidden lg:table-column w-[110px]">
            <col class="hidden xl:table-column w-[160px]">
            <col class="w-[90px]">
            <col class="w-12">
        </colgroup>
        <thead>
            <tr class="bg-muted/40 text-[11px] uppercase tracking-wider text-muted-foreground">
                <th class="text-left font-medium px-4 py-2.5">Rule</th>
                <th class="hidden md:table-cell text-left font-medium px-4 py-2.5">Trigger</th>
                <th class="hidden lg:table-cell text-left font-medium px-4 py-2.5">Threshold</th>
                <th class="hidden lg:table-cell text-left font-medium px-4 py-2.5">Window</th>
                <th class="hidden xl:table-cell text-left font-medium px-4 py-2.5">Channels</th>
                <th class="text-left font-medium px-4 py-2.5">State</th>
                <th class="px-3 py-2.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rulesOverview->builtInRules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php echo $__env->make('jobs-monitor::settings.alerts._built-in-row', ['b' => $b, 'editing' => $editing], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\settings\alerts\_rules.blade.php ENDPATH**/ ?>