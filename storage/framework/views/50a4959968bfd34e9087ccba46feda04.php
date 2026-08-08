<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-center min-h-[calc(100vh-6rem)]">
    <div class="w-full max-w-lg rounded-2xl border border-border bg-card text-card-foreground shadow-2xl">

        
        <div class="flex flex-col items-center gap-4 pt-8 px-8 pb-5 text-center">
            <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-warning/10 text-warning ring-2 ring-inset ring-warning/20">
                <i data-lucide="table-2" class="text-[32px]"></i>
            </span>
            <div>
                <h1 class="text-xl font-bold tracking-tight">Migrations not applied</h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    The <code class="px-1.5 py-0.5 rounded bg-muted font-mono text-xs">jobs_monitor_*</code>
                    tables are missing from connection
                    <code class="px-1.5 py-0.5 rounded bg-muted font-mono text-xs"><?php echo e($jmActiveConn); ?></code>.
                    Monitoring cannot run until the tables are created.
                </p>
            </div>
        </div>

        <div class="space-y-4 px-8 pb-8">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('jobs_monitor_error')): ?>
                <div class="flex items-start gap-2 rounded-lg border border-destructive/25 bg-destructive/10 text-destructive px-4 py-3 text-sm">
                    <i data-lucide="alert-circle" class="text-[14px] mt-0.5 shrink-0"></i>
                    <span><?php echo e(session('jobs_monitor_error')); ?></span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="rounded-xl border border-border bg-muted/30 p-5 space-y-3">
                <p class="text-sm font-semibold flex items-center gap-2">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-brand/15 text-brand text-[11px] font-bold ring-1 ring-brand/20">1</span>
                    Run migrations automatically
                </p>
                <p class="text-xs text-muted-foreground">
                    Creates all <code class="px-1 rounded bg-muted">jobs_monitor_*</code> tables on
                    <code class="px-1 rounded bg-muted"><?php echo e($jmActiveConn); ?></code>.
                </p>
                <form id="jm-migrate-form" method="POST" action="<?php echo e(route('jobs-monitor.settings.database.run-migrations')); ?>"
                      onsubmit="jmStartMigrate(this)">
                    <?php echo csrf_field(); ?>
                    <button id="jm-migrate-btn" type="submit"
                            class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium bg-brand text-white hover:bg-brand/90 transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                        <i id="jm-migrate-icon" data-lucide="database" class="text-[14px] shrink-0"></i>
                        <svg id="jm-migrate-spinner" class="hidden animate-spin h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span id="jm-migrate-label">Run Migrations</span>
                    </button>
                </form>
            </div>

            
            <div class="rounded-xl border border-border bg-muted/30 p-5 space-y-3">
                <p class="text-sm font-semibold flex items-center gap-2">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-muted text-muted-foreground text-[11px] font-bold ring-1 ring-border">2</span>
                    Or run manually from the terminal
                </p>
                <div class="rounded-lg bg-muted px-4 py-2.5 font-mono text-xs select-all text-foreground">
                    php artisan migrate
                </div>
            </div>

            <div class="flex justify-center pt-1">
                <a href="<?php echo e(route('jobs-monitor.settings.database')); ?>"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
                    <i data-lucide="settings" class="text-[14px]"></i>
                    View Database Settings
                </a>
            </div>

        </div>
    </div>
</div>

<script>
function jmStartMigrate(form) {
    var btn     = document.getElementById('jm-migrate-btn');
    var icon    = document.getElementById('jm-migrate-icon');
    var spinner = document.getElementById('jm-migrate-spinner');
    var label   = document.getElementById('jm-migrate-label');
    btn.disabled = true;
    icon.classList.add('hidden');
    spinner.classList.remove('hidden');
    label.textContent = 'Applying migrations…';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('jobs-monitor::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\errors\db-not-migrated.blade.php ENDPATH**/ ?>