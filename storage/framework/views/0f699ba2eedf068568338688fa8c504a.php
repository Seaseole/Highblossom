<?php $__env->startSection('content'); ?>
    <?php
        $baseParams = array_filter([
            'period' => $vm->period,
            'search' => $vm->search,
            'status' => $vm->status,
            'queue' => $vm->queue,
            'connection' => $vm->connection,
            'failure_category' => $vm->failureCategory,
        ]);

        $statusOptions = [
            '' => 'All statuses',
            'processing' => 'Processing',
            'processed' => 'Processed',
            'failed' => 'Failed',
        ];

        $failureCategoryOptions = [
            '' => 'All categories',
            'transient' => 'Transient',
            'permanent' => 'Permanent',
            'critical' => 'Critical',
            'unknown' => 'Unknown',
        ];

        $activeFilters = [
            ['key' => 'search', 'name' => 'Search', 'value' => $vm->search],
            ['key' => 'status', 'name' => 'Status', 'value' => $vm->status],
            ['key' => 'queue', 'name' => 'Queue', 'value' => $vm->queue],
            ['key' => 'connection', 'name' => 'Connection', 'value' => $vm->connection],
            ['key' => 'failure_category', 'name' => 'Category', 'value' => $vm->failureCategory],
        ];
        $activeFilters = array_values(array_filter($activeFilters, static fn (array $f) => $f['value'] !== ''));

        $inputBase = 'h-9 rounded-md border border-input bg-card text-sm text-foreground px-3 focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring transition-[box-shadow,border-color]';
        $inputActive = 'border-brand ring-2 ring-brand/20 bg-brand/5';
    ?>

    
    <div class="flex flex-wrap items-end justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Dashboard</h1>
            <p class="text-sm text-muted-foreground mt-1">Real-time overview of queue health and recent jobs.</p>
        </div>
        <a href="<?php echo e(url()->full()); ?>"
           class="inline-flex items-center gap-1.5 h-9 px-3 rounded-md border border-border bg-card text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground transition-colors shadow-xs">
            <i data-lucide="refresh-cw" class="text-[14px]"></i>
            Refresh
        </a>
    </div>

    
    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-xs mb-6">
        <div class="p-4 space-y-4">
            
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Period</span>
                <div class="inline-flex rounded-lg bg-muted p-1 gap-0.5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vm->periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a href="<?php echo e(route('jobs-monitor.dashboard', array_merge($baseParams, ['period' => $key]))); ?>"
                           class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md transition-all
                                  <?php echo e($vm->period === $key
                                      ? 'bg-card text-foreground shadow-xs ring-1 ring-border'
                                      : 'text-muted-foreground hover:text-foreground'); ?>">
                            <?php echo e($key); ?>

                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>

            
            <form method="GET" action="<?php echo e(route('jobs-monitor.dashboard')); ?>" class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="period" value="<?php echo e($vm->period); ?>">

                <div class="relative">
                    <i data-lucide="search" class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[14px] text-muted-foreground pointer-events-none"></i>
                    <input type="text" name="search" value="<?php echo e($vm->search); ?>" placeholder="Search by job class…"
                           class="pl-8 w-60 <?php echo e($inputBase); ?> <?php echo e($vm->search !== '' ? $inputActive : ''); ?>">
                </div>

                <?php
                    $queueOptions = ['' => 'All queues'] + array_combine($vm->availableQueues, $vm->availableQueues);
                    $connOptions  = ['' => 'All connections'] + array_combine($vm->availableConnections, $vm->availableConnections);
                ?>

                <?php echo $__env->make('jobs-monitor::partials.select', [
                    'name' => 'status', 'value' => $vm->status, 'options' => $statusOptions, 'placeholder' => 'All statuses',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('jobs-monitor::partials.select', [
                    'name' => 'queue', 'value' => $vm->queue, 'options' => $queueOptions, 'placeholder' => 'All queues',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('jobs-monitor::partials.select', [
                    'name' => 'connection', 'value' => $vm->connection, 'options' => $connOptions, 'placeholder' => 'All connections',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('jobs-monitor::partials.select', [
                    'name' => 'failure_category', 'value' => $vm->failureCategory, 'options' => $failureCategoryOptions, 'placeholder' => 'All categories',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <button type="submit"
                        class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-md border border-brand/30 bg-brand/10 text-brand text-sm font-medium hover:bg-brand/15 hover:border-brand/40 transition-colors">
                    <i data-lucide="filter" class="text-[14px]"></i>
                    Apply
                </button>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($activeFilters) > 0): ?>
                    <a href="<?php echo e(route('jobs-monitor.dashboard', ['period' => $vm->period])); ?>"
                       class="inline-flex items-center gap-1 text-xs font-medium text-muted-foreground hover:text-foreground px-2 py-1.5">
                        <i data-lucide="x" class="text-[13px]"></i>
                        Clear all
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </form>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($activeFilters) > 0): ?>
                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $activeFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $removeParams = $baseParams;
                            unset($removeParams[$filter['key']]);
                        ?>
                        <span class="inline-flex items-center gap-1.5 pl-2.5 pr-1 py-1 rounded-md text-xs font-medium bg-secondary text-secondary-foreground ring-1 ring-inset ring-border">
                            <span class="text-muted-foreground"><?php echo e($filter['name']); ?>:</span>
                            <span class="font-semibold"><?php echo e($filter['value']); ?></span>
                            <a href="<?php echo e(route('jobs-monitor.dashboard', $removeParams)); ?>"
                               class="inline-flex items-center justify-center w-4 h-4 rounded-sm text-muted-foreground hover:bg-destructive/15 hover:text-destructive transition-colors"
                               title="Remove <?php echo e($filter['name']); ?>">
                                <i data-lucide="x" class="text-[11px]"></i>
                            </a>
                        </span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <?php echo $__env->make('jobs-monitor::partials.time-series-chart', ['period' => $vm->period], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php
        $summaryCards = [
            ['key' => 'total',        'label' => 'Total',        'icon' => 'layers',         'accent' => 'text-foreground',   'iconBg' => 'bg-muted text-muted-foreground'],
            ['key' => 'processed',    'label' => 'Processed',    'icon' => 'check-circle-2', 'accent' => 'text-success',      'iconBg' => 'bg-success/10 text-success'],
            ['key' => 'failed',       'label' => 'Failed',       'icon' => 'alert-octagon',  'accent' => $vm->statusCounts['failed'] > 0 ? 'text-destructive' : 'text-foreground', 'iconBg' => 'bg-destructive/10 text-destructive'],
            ['key' => 'processing',   'label' => 'Processing',   'icon' => 'loader',         'accent' => $vm->statusCounts['processing'] > 0 ? 'text-warning' : 'text-foreground', 'iconBg' => 'bg-warning/10 text-warning'],
            ['key' => 'success_rate', 'label' => 'Success Rate', 'icon' => 'trending-up',    'accent' => 'text-foreground',   'iconBg' => 'bg-brand/10 text-brand'],
        ];
    ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $summaryCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php
                $value = $card['key'] === 'success_rate'
                    ? $vm->successRate()
                    : number_format($vm->statusCounts[$card['key']]);
            ?>
            <div class="group relative overflow-hidden rounded-xl border border-border bg-card text-card-foreground p-4 shadow-xs transition-shadow hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-muted-foreground"><?php echo e($card['label']); ?></span>
                    <span class="flex h-7 w-7 items-center justify-center rounded-md <?php echo e($card['iconBg']); ?>">
                        <i data-lucide="<?php echo e($card['icon']); ?>" class="text-[14px] <?php echo e($card['key'] === 'processing' && $vm->statusCounts['processing'] > 0 ? 'animate-spin' : ''); ?>"></i>
                    </span>
                </div>
                <div class="mt-2 text-2xl font-bold tracking-tight tabular-nums <?php echo e($card['accent']); ?>" data-summary-card="<?php echo e($card['key']); ?>"><?php echo e($value); ?></div>
                <div aria-hidden="true" class="pointer-events-none absolute -bottom-8 -right-8 h-24 w-24 rounded-full bg-gradient-to-tr from-transparent to-brand/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
    <?php echo $__env->make('jobs-monitor::partials.summary-auto-refresh', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vm->failuresTotal > 0): ?>
        <?php
            $fSortUrl = fn(string $col) => route('jobs-monitor.dashboard', array_merge($baseParams, [
                'page' => $vm->jobsPage, 'sort' => $vm->jobsSort, 'dir' => $vm->jobsDir,
                'fpage' => 1, 'fsort' => $col,
                'fdir' => ($vm->failuresSort === $col && $vm->failuresDir === 'asc') ? 'desc' : 'asc',
            ]));
            // Filter state forwarded to the "Select all matching" endpoint so
            // the cross-page selection respects whatever the operator filtered
            // the Failed Jobs block down to.
            $failuresCandidatesUrl = route('jobs-monitor.failures.bulk.candidates', array_filter([
                'period' => $vm->period,
                'search' => $vm->search !== '' ? $vm->search : null,
                'queue' => $vm->queue !== '' ? $vm->queue : null,
                'connection' => $vm->connection !== '' ? $vm->connection : null,
                'failure_category' => $vm->failureCategory !== '' ? $vm->failureCategory : null,
            ], static fn ($v) => $v !== null && $v !== ''));
            $fIcon = fn(string $col) => $vm->failuresSort === $col
                ? ($vm->failuresDir === 'asc' ? 'arrow-up' : 'arrow-down')
                : 'chevrons-up-down';
            $fSortClass = fn(string $col) => $vm->failuresSort === $col
                ? 'text-destructive'
                : 'text-muted-foreground';
        ?>
        <div class="rounded-xl border border-destructive/30 bg-card text-card-foreground shadow-xs mb-6 overflow-hidden" data-collapsible="failed-jobs">
            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-destructive/20 bg-destructive/5">
                <button type="button"
                        class="flex-1 flex items-center gap-3 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-ring rounded-md"
                        onclick="__jmToggleCollapsible('failed-jobs')"
                        aria-controls="failed-jobs-body"
                        data-collapsible-trigger>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-destructive/15 text-destructive ring-1 ring-inset ring-destructive/20">
                        <i data-lucide="alert-triangle" class="text-[16px]"></i>
                    </span>
                    <div class="flex-1">
                        <h2 class="text-sm font-semibold text-destructive">Failed Jobs</h2>
                        <p class="text-xs text-muted-foreground"><?php echo e(number_format($vm->failuresTotal)); ?> total · sorted by <?php echo e($vm->failuresSort); ?></p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-muted-foreground" data-collapsible-label>Hide</span>
                    <span class="flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground hover:text-foreground transition-transform" data-collapsible-caret>
                        <i data-lucide="chevron-up" class="text-[16px]"></i>
                    </span>
                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vm->retryEnabled): ?>
                    <button type="button"
                            class="inline-flex items-center gap-1.5 h-8 px-3 text-xs font-medium rounded-md border border-destructive/30 bg-card hover:bg-destructive/10 transition-colors text-destructive"
                            data-jm-bulk-select-all="failures"
                            title="Select every matching failure across all pages">
                        <i data-lucide="check-check" class="text-[14px]"></i>
                        Select all <?php echo e(number_format($vm->failuresTotal)); ?> matching
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div id="failed-jobs-body" data-collapsible-body>
            <div>
                <table class="w-full text-sm table-fixed"
                       data-jm-bulk-scope="failures"
                       data-jm-bulk-candidates="<?php echo e($failuresCandidatesUrl); ?>"
                       data-jm-bulk-retry="<?php echo e(route('jobs-monitor.dlq.bulk.retry')); ?>"
                       data-jm-bulk-noun="job">
                    <colgroup>
                        <col class="w-10">
                        <col>
                        <col class="hidden md:table-column w-[110px]">
                        <col class="hidden xl:table-column w-[70px]">
                        <col class="w-[150px]">
                        <col class="hidden 2xl:table-column w-[100px]">
                        <col class="hidden lg:table-column w-[130px]">
                        <col class="hidden xl:table-column">
                        <col class="w-12">
                    </colgroup>
                    <thead>
                        <tr class="bg-muted/40 text-[11px] uppercase tracking-wider text-muted-foreground">
                            <th class="px-3 py-2.5">
                                <?php echo $__env->make('jobs-monitor::partials.checkbox', [
                                    'ariaLabel' => 'Select all failures on page',
                                    'attributes' => 'data-jm-bulk-page-select',
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </th>
                            <th class="text-left font-medium px-3 py-2.5"><a href="<?php echo e($fSortUrl('job_class')); ?>" class="inline-flex items-center gap-1 hover:text-foreground <?php echo e($fSortClass('job_class')); ?>">Job <i data-lucide="<?php echo e($fIcon('job_class')); ?>" class="text-[11px]"></i></a></th>
                            <th class="hidden md:table-cell text-left font-medium px-3 py-2.5">Queue</th>
                            <th class="hidden xl:table-cell text-left font-medium px-3 py-2.5">Att.</th>
                            <th class="text-left font-medium px-3 py-2.5"><a href="<?php echo e($fSortUrl('started_at')); ?>" class="inline-flex items-center gap-1 hover:text-foreground <?php echo e($fSortClass('started_at')); ?>">Failed At <i data-lucide="<?php echo e($fIcon('started_at')); ?>" class="text-[11px]"></i></a></th>
                            <th class="hidden 2xl:table-cell text-left font-medium px-3 py-2.5"><a href="<?php echo e($fSortUrl('duration_ms')); ?>" class="inline-flex items-center gap-1 hover:text-foreground <?php echo e($fSortClass('duration_ms')); ?>">Duration <i data-lucide="<?php echo e($fIcon('duration_ms')); ?>" class="text-[11px]"></i></a></th>
                            <th class="hidden lg:table-cell text-left font-medium px-3 py-2.5">Category</th>
                            <th class="hidden xl:table-cell text-left font-medium px-3 py-2.5">Exception</th>
                            <th class="px-3 py-2.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vm->failures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="cursor-pointer <?php echo e($loop->even ? 'bg-destructive/10' : 'bg-destructive/5'); ?> hover:bg-destructive/15 transition-colors" onclick="this.nextElementSibling.classList.toggle('hidden')">
                                <td class="px-3 py-3 align-middle" onclick="event.stopPropagation()">
                                    <?php echo $__env->make('jobs-monitor::partials.checkbox', [
                                        'value' => $job['uuid'],
                                        'ariaLabel' => 'Select '.$job['short_class'],
                                        'attributes' => 'data-jm-bulk-row data-retryable="'.(($vm->retryEnabled && $job['has_payload']) ? '1' : '0').'"',
                                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </td>
                                <td class="px-3 py-3 font-medium truncate" title="<?php echo e($job['job_class']); ?>"><?php echo e($job['short_class']); ?></td>
                                <td class="hidden md:table-cell px-3 py-3 text-muted-foreground truncate"><code class="rounded bg-muted px-1.5 py-0.5 text-[11px] font-mono"><?php echo e($job['queue']); ?></code></td>
                                <td class="hidden xl:table-cell px-3 py-3 text-muted-foreground tabular-nums"><?php echo e($job['attempt']); ?></td>
                                <td class="px-3 py-3 text-muted-foreground tabular-nums text-xs truncate"><?php echo e($job['finished_at'] ?? $job['started_at']); ?></td>
                                <td class="hidden 2xl:table-cell px-3 py-3 text-muted-foreground tabular-nums"><?php echo e($job['duration_formatted']); ?></td>
                                <td class="hidden lg:table-cell px-3 py-3 truncate">
                                    <?php echo $__env->make('jobs-monitor::partials.failure-category-badge', [
                                        'value' => $job['failure_category'],
                                        'label' => $job['failure_category_label'],
                                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </td>
                                <td class="hidden xl:table-cell px-3 py-3 text-destructive text-xs truncate" title="<?php echo e($job['exception'] ?? ''); ?>"><?php echo e(\Illuminate\Support\Str::limit($job['exception'] ?? '', 50)); ?></td>
                                <td class="px-3 py-3 text-right" onclick="event.stopPropagation()">
                                    <?php echo $__env->make('jobs-monitor::partials.retry-actions', ['job' => $job, 'retryEnabled' => $vm->retryEnabled], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </td>
                            </tr>
                            <tr class="hidden">
                                <td colspan="9" class="px-5 py-4 bg-muted/30 animate-slide-down">
                                    <div class="flex justify-end mb-3">
                                        <a href="<?php echo e(route('jobs-monitor.detail', ['uuid' => $job['uuid'], 'attempt' => $job['attempt']])); ?>"
                                           class="inline-flex items-center gap-1.5 h-8 px-3 rounded-md bg-destructive text-destructive-foreground text-xs font-semibold hover:bg-destructive/90 transition-colors shadow-xs">
                                            View details &amp; retry timeline
                                            <i data-lucide="arrow-right" class="text-[13px]"></i>
                                        </a>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                                            ['UUID', $job['uuid'], true],
                                            ['Full Class', $job['job_class'], true],
                                            ['Connection', $job['connection'], false],
                                            ['Started At', $job['started_at'], false],
                                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $mono]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <div>
                                                <span class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider"><?php echo e($label); ?></span>
                                                <p class="text-sm <?php echo e($mono ? 'font-mono break-all' : ''); ?>"><?php echo e($val); ?></p>
                                            </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job['payload']): ?>
                                        <div class="mb-3">
                                            <span class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider">Payload</span>
                                            <pre class="mt-1 bg-card border border-border rounded-lg p-3 text-xs overflow-x-auto whitespace-pre-wrap break-words font-mono"><?php echo e(json_encode($job['payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job['exception']): ?>
                                        <div>
                                            <span class="text-[10px] font-medium text-destructive uppercase tracking-wider">Exception</span>
                                            <pre class="mt-1 bg-destructive/10 border border-destructive/20 rounded-lg p-3 text-xs text-destructive overflow-x-auto whitespace-pre-wrap break-words font-mono"><?php echo e($job['exception']); ?></pre>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vm->failuresLastPage > 1): ?>
                <?php echo $__env->make('jobs-monitor::partials.pagination', [
                    'currentPage' => $vm->failuresPage,
                    'lastPage' => $vm->failuresLastPage,
                    'pageParam' => 'fpage',
                    'extraParams' => array_merge($baseParams, [
                        'page' => $vm->jobsPage, 'sort' => $vm->jobsSort, 'dir' => $vm->jobsDir,
                        'fsort' => $vm->failuresSort, 'fdir' => $vm->failuresDir,
                    ]),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <script>
        function __jmToggleCollapsible(key) {
            var root = document.querySelector('[data-collapsible="' + key + '"]');
            if (!root) return;
            var collapsed = root.getAttribute('data-collapsed') === '1';
            __jmSetCollapsed(root, !collapsed);
            try { localStorage.setItem('jm-collapsed-' + key, collapsed ? '0' : '1'); } catch (e) {}
        }
        function __jmSetCollapsed(root, collapsed) {
            var body = root.querySelector('[data-collapsible-body]');
            var caret = root.querySelector('[data-collapsible-caret]');
            var label = root.querySelector('[data-collapsible-label]');
            root.setAttribute('data-collapsed', collapsed ? '1' : '0');
            if (body) body.classList.toggle('hidden', collapsed);
            if (caret) caret.style.transform = collapsed ? 'rotate(180deg)' : 'rotate(0deg)';
            if (label) label.textContent = collapsed ? 'Show' : 'Hide';
        }
        (function () {
            function hydrate() {
                document.querySelectorAll('[data-collapsible]').forEach(function (root) {
                    var key = root.getAttribute('data-collapsible');
                    var stored = null;
                    try { stored = localStorage.getItem('jm-collapsed-' + key); } catch (e) {}
                    if (stored === '1') __jmSetCollapsed(root, true);
                });
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', hydrate);
            } else {
                hydrate();
            }
        })();
    </script>

    
    <?php
        $jSortUrl = fn(string $col) => route('jobs-monitor.dashboard', array_merge($baseParams, [
            'page' => 1, 'sort' => $col,
            'dir' => ($vm->jobsSort === $col && $vm->jobsDir === 'asc') ? 'desc' : 'asc',
            'fpage' => $vm->failuresPage, 'fsort' => $vm->failuresSort, 'fdir' => $vm->failuresDir,
        ]));
        $jIcon = fn(string $col) => $vm->jobsSort === $col
            ? ($vm->jobsDir === 'asc' ? 'arrow-up' : 'arrow-down')
            : 'chevrons-up-down';
        $jSortClass = fn(string $col) => $vm->jobsSort === $col
            ? 'text-foreground'
            : 'text-muted-foreground';
    ?>
    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-xs overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3.5 border-b border-border">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-secondary text-secondary-foreground">
                <i data-lucide="list" class="text-[16px]"></i>
            </span>
            <div>
                <h2 class="text-sm font-semibold">All Jobs</h2>
                <p class="text-xs text-muted-foreground"><?php echo e(number_format($vm->jobsTotal)); ?> total</p>
            </div>
        </div>
        <div>
            <table class="w-full text-sm table-fixed">
                <colgroup>
                    <col class="w-[130px]">
                    <col>
                    <col class="hidden xl:table-column w-[120px]">
                    <col class="hidden md:table-column w-[110px]">
                    <col class="hidden 2xl:table-column w-[70px]">
                    <col class="w-[150px]">
                    <col class="hidden lg:table-column w-[100px]">
                    <col class="w-12">
                </colgroup>
                <thead>
                    <tr class="bg-muted/40 text-[11px] uppercase tracking-wider text-muted-foreground">
                        <th class="text-left font-medium px-3 py-2.5"><a href="<?php echo e($jSortUrl('status')); ?>" class="inline-flex items-center gap-1 hover:text-foreground <?php echo e($jSortClass('status')); ?>">Status <i data-lucide="<?php echo e($jIcon('status')); ?>" class="text-[11px]"></i></a></th>
                        <th class="text-left font-medium px-3 py-2.5"><a href="<?php echo e($jSortUrl('job_class')); ?>" class="inline-flex items-center gap-1 hover:text-foreground <?php echo e($jSortClass('job_class')); ?>">Job <i data-lucide="<?php echo e($jIcon('job_class')); ?>" class="text-[11px]"></i></a></th>
                        <th class="hidden xl:table-cell text-left font-medium px-3 py-2.5">Connection</th>
                        <th class="hidden md:table-cell text-left font-medium px-3 py-2.5">Queue</th>
                        <th class="hidden 2xl:table-cell text-left font-medium px-3 py-2.5">Att.</th>
                        <th class="text-left font-medium px-3 py-2.5"><a href="<?php echo e($jSortUrl('started_at')); ?>" class="inline-flex items-center gap-1 hover:text-foreground <?php echo e($jSortClass('started_at')); ?>">Started At <i data-lucide="<?php echo e($jIcon('started_at')); ?>" class="text-[11px]"></i></a></th>
                        <th class="hidden lg:table-cell text-left font-medium px-3 py-2.5"><a href="<?php echo e($jSortUrl('duration_ms')); ?>" class="inline-flex items-center gap-1 hover:text-foreground <?php echo e($jSortClass('duration_ms')); ?>">Duration <i data-lucide="<?php echo e($jIcon('duration_ms')); ?>" class="text-[11px]"></i></a></th>
                        <th class="px-3 py-2.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $vm->jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $rowBg = $job['is_failed']
                                ? 'bg-destructive/5 hover:bg-destructive/10'
                                : ($loop->even ? 'bg-muted/40 hover:bg-muted/60' : 'bg-card hover:bg-muted/30');
                        ?>
                        <tr class="cursor-pointer transition-colors <?php echo e($rowBg); ?>" onclick="this.nextElementSibling.classList.toggle('hidden')">
                            <td class="px-3 py-3 truncate">
                                <?php echo $__env->make('jobs-monitor::partials.status-badge', ['value' => $job['status']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </td>
                            <td class="px-3 py-3 font-medium truncate" title="<?php echo e($job['job_class']); ?>"><?php echo e($job['short_class']); ?></td>
                            <td class="hidden xl:table-cell px-3 py-3 text-muted-foreground truncate"><?php echo e($job['connection']); ?></td>
                            <td class="hidden md:table-cell px-3 py-3 text-muted-foreground truncate"><code class="rounded bg-muted px-1.5 py-0.5 text-[11px] font-mono"><?php echo e($job['queue']); ?></code></td>
                            <td class="hidden 2xl:table-cell px-3 py-3 text-muted-foreground tabular-nums"><?php echo e($job['attempt']); ?></td>
                            <td class="px-3 py-3 text-muted-foreground tabular-nums text-xs truncate"><?php echo e($job['started_at']); ?></td>
                            <td class="hidden lg:table-cell px-3 py-3 text-muted-foreground tabular-nums"><?php echo e($job['duration_formatted']); ?></td>
                            <td class="px-3 py-3 text-right" onclick="event.stopPropagation()">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job['is_failed']): ?>
                                    <?php echo $__env->make('jobs-monitor::partials.retry-actions', ['job' => $job, 'retryEnabled' => $vm->retryEnabled], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                        <tr class="hidden">
                            <td colspan="8" class="px-5 py-4 bg-muted/30 animate-slide-down">
                                <div class="flex justify-end mb-3">
                                    <a href="<?php echo e(route('jobs-monitor.detail', ['uuid' => $job['uuid'], 'attempt' => $job['attempt']])); ?>"
                                       class="inline-flex items-center gap-1.5 h-8 px-3 rounded-md bg-primary text-primary-foreground text-xs font-semibold hover:bg-primary/90 transition-colors shadow-xs">
                                        View details &amp; retry timeline
                                        <i data-lucide="arrow-right" class="text-[13px]"></i>
                                    </a>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                                        ['UUID', $job['uuid'], true],
                                        ['Full Class', $job['job_class'], true],
                                        ['Finished At', $job['finished_at'] ?? '—', false],
                                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $mono]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <div>
                                            <span class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider"><?php echo e($label); ?></span>
                                            <p class="text-sm <?php echo e($mono ? 'font-mono break-all' : ''); ?>"><?php echo e($val); ?></p>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job['failure_category']): ?>
                                    <div class="mt-3">
                                        <span class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider">Failure Category</span>
                                        <p class="text-sm mt-1">
                                            <?php echo $__env->make('jobs-monitor::partials.failure-category-badge', [
                                                'value' => $job['failure_category'],
                                                'label' => $job['failure_category_label'],
                                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        </p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job['payload']): ?>
                                    <div class="mt-3">
                                        <span class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider">Payload</span>
                                        <pre class="mt-1 bg-card border border-border rounded-lg p-3 text-xs overflow-x-auto whitespace-pre-wrap break-words font-mono"><?php echo e(json_encode($job['payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job['exception']): ?>
                                    <div class="mt-3">
                                        <span class="text-[10px] font-medium text-destructive uppercase tracking-wider">Exception</span>
                                        <pre class="mt-1 bg-destructive/10 border border-destructive/20 rounded-lg p-3 text-xs text-destructive overflow-x-auto whitespace-pre-wrap break-words font-mono"><?php echo e($job['exception']); ?></pre>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-muted-foreground">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-muted">
                                        <i data-lucide="inbox" class="text-xl"></i>
                                    </div>
                                    <p class="text-sm font-medium text-foreground">No jobs found</p>
                                    <p class="text-xs">No jobs match the selected filters.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vm->jobsLastPage > 1): ?>
            <?php echo $__env->make('jobs-monitor::partials.pagination', [
                'currentPage' => $vm->jobsPage,
                'lastPage' => $vm->jobsLastPage,
                'pageParam' => 'page',
                'extraParams' => array_merge($baseParams, [
                    'sort' => $vm->jobsSort, 'dir' => $vm->jobsDir,
                    'fpage' => $vm->failuresPage, 'fsort' => $vm->failuresSort, 'fdir' => $vm->failuresDir,
                ]),
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vm->retryEnabled): ?>
        <?php echo $__env->make('jobs-monitor::partials.bulk-bar', [
            'scope' => 'failures',
            'retryEnabled' => true,
            'showDelete' => false,
            'noun' => 'job',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('jobs-monitor::partials.bulk-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php echo $__env->make('jobs-monitor::partials.kebab-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('jobs-monitor::partials.confirm-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('jobs-monitor::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\dashboard.blade.php ENDPATH**/ ?>