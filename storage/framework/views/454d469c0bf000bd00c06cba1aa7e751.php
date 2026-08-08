<?php $__env->startSection('title', 'Security Violation #' . $log->id); ?>
<?php $__env->startSection('header_title', 'Security Alert Details'); ?>

<?php $__env->startSection('header_actions'); ?>
    <a href="<?php echo e(url(config('auditify.route_prefix', 'auditify') . '/security-logs')); ?>" class="btn btn-secondary">
        &larr; Back to Alerts
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
    .security-layout {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 28px;
        align-items: start;
    }
    .severity-panel {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        padding: 24px;
        text-align: center;
    }
    .severity-title {
        font-size: 13px;
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }
    .severity-pill-large {
        display: inline-block;
        padding: 10px 24px;
        border-radius: 30px;
        font-size: 18px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 24px;
        letter-spacing: 1px;
    }
    .large-critical { background-color: rgba(239, 68, 68, 0.15); color: #ef4444; border: 2px solid rgba(239, 68, 68, 0.4); }
    .large-high { background-color: rgba(249, 115, 22, 0.15); color: #f97316; border: 2px solid rgba(249, 115, 22, 0.4); }
    .large-medium { background-color: rgba(234, 179, 8, 0.15); color: #eab308; border: 2px solid rgba(234, 179, 8, 0.4); }
    .large-low { background-color: rgba(59, 130, 246, 0.15); color: #3b82f6; border: 2px solid rgba(59, 130, 246, 0.4); }

    .meta-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .meta-item {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 10px;
        font-size: 13px;
    }
    .meta-label {
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-size: 11px;
    }
    .meta-val {
        color: var(--card-text-highlight);
    }

    .alert-panel {
        background-color: rgba(239, 68, 68, 0.02);
        border: 1px solid rgba(239, 68, 68, 0.1);
        border-radius: var(--border-radius);
        padding: 24px;
    }
    .alert-headline {
        font-size: 20px;
        font-weight: 700;
        color: var(--card-text-highlight);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-headline span {
        color: #ef4444;
    }
    .alert-description {
        font-size: 15px;
        line-height: 1.6;
        color: var(--text-secondary);
        margin-bottom: 24px;
        background-color: var(--box-bg-light);
        padding: 16px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }
    .recommends-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 24px;
    }
    .recommends-item {
        font-size: 13px;
        color: var(--text-secondary);
        position: relative;
        padding-left: 28px;
        line-height: 1.6;
    }
    .recommends-item::before {
        content: '🛡️';
        position: absolute;
        left: 0;
        top: 2px;
    }

    @media (max-width: 1024px) {
        .security-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="security-layout">
    <!-- Left Column: Severity & User Context -->
    <div style="display: flex; flex-direction: column; gap: 28px;">
        <div class="severity-panel">
            <div class="severity-title">Threat Level</div>
            <div class="severity-pill-large large-<?php echo e($log->severity); ?>">
                <?php echo e($log->severity); ?>

            </div>
            
            <div class="meta-list" style="text-align: left;">
                <div class="meta-item">
                    <span class="meta-label">Alert ID</span>
                    <span class="meta-val" style="font-family: var(--font-mono);">#<?php echo e($log->id); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Responsible User</span>
                    <span class="meta-val"><?php echo e($log->user?->name ?? 'System / Guest'); ?></span>
                </div>
                <?php
                    $emailKey = config('auditify.user_fields.email', 'email');
                    $usernameKey = config('auditify.user_fields.username', 'username');
                    $phoneKey = config('auditify.user_fields.phone', 'phone');
                ?>
                <div class="meta-item">
                    <span class="meta-label">User Email</span>
                    <span class="meta-val"><?php echo e($log->user?->{$emailKey} ?? 'N/A'); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Username</span>
                    <span class="meta-val"><?php echo e($log->user?->{$usernameKey} ?? 'N/A'); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Phone</span>
                    <span class="meta-val"><?php echo e($log->user?->{$phoneKey} ?? 'N/A'); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">IP Address</span>
                    <span class="meta-val" style="font-family: var(--font-mono);"><?php echo e($log->ip_address ?? 'N/A'); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">User Agent</span>
                    <span class="meta-val" style="font-size: 11px; word-break: break-all; text-align: right; max-width: 160px; color: var(--text-secondary);" title="<?php echo e($log->user_agent); ?>"><?php echo e($log->user_agent ?? 'N/A'); ?></span>
                </div>
                <div class="meta-item" style="border-bottom: none;">
                    <span class="meta-label">Triggered At</span>
                    <span class="meta-val"><span class="audit-timestamp" data-timestamp="<?php echo e($log->created_at->toIso8601String()); ?>"><?php echo e($log->created_at->format('Y-m-d H:i:s')); ?></span></span>
                </div>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$log->is_read): ?>
            <div class="card" style="padding: 24px;">
                <h3 style="font-size: 14px; font-weight: 700; color: var(--card-text-highlight); margin-bottom: 12px;">Resolution Actions</h3>
                <form method="POST" action="<?php echo e(url(config('auditify.route_prefix', 'auditify') . '/security-logs/' . $log->id . '/read')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary" style="width: 100%; background-color: #10b981;">
                        Mark Alert as Resolved
                    </button>
                </form>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Right Column: Alert Description & Investigation details -->
    <div class="card alert-panel">
        <h2 class="alert-headline">
            <span>⚠️</span> <?php echo e($log->title); ?>

        </h2>
        
        <div class="alert-description">
            <?php echo e($log->description); ?>

        </div>

        <h3 style="font-size: 14px; font-weight: 700; color: var(--card-text-highlight); margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
            Suggested Investigation Actions
        </h3>
        <?php
            // Extract IP address from description to build an active geo lookup link
            preg_match('/\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}\b/', $log->description, $ipMatches);
            $ipInDesc = $log->ip_address ?: ($ipMatches[0] ?? null);
        ?>
        <ul class="recommends-list">
            <li class="recommends-item">
                Validate if user <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->user): ?><a href="<?php echo e(url(config('auditify.route_prefix', 'auditify') . '/action-logs?user_id=' . $log->user_id)); ?>" style="color: var(--color-indigo); font-weight: 600; text-decoration: underline;"><?php echo e($log->user->name); ?></a><?php else: ?><strong>Guest</strong><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> was authorized to perform the operations described.
            </li>
            <li class="recommends-item">
                Cross-reference user activity records in <a href="<?php echo e(url(config('auditify.route_prefix', 'auditify') . '/activity-logs?start_date=' . $log->created_at->format('Y-m-d') . '&end_date=' . $log->created_at->format('Y-m-d') . ($log->user_id ? '&user_id=' . $log->user_id : ''))); ?>" style="color: var(--color-indigo); font-weight: 600; text-decoration: underline;">Activity Logs</a> for the timeframe around <strong><?php echo e($log->created_at->format('Y-m-d')); ?></strong> to detect wider suspicious footprints.
            </li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ipInDesc): ?>
                <li class="recommends-item">
                    Verify the originating IP address client location on <a href="https://ipinfo.io/<?php echo e($ipInDesc); ?>" target="_blank" style="color: var(--color-indigo); font-weight: 600; text-decoration: underline;">IPInfo (<?php echo e($ipInDesc); ?>)</a> to check if the session might be hijacked.
                </li>
            <?php else: ?>
                <li class="recommends-item">
                    Verify the originating IP address client location to check if the session might be hijacked.
                </li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <li class="recommends-item">
                If brute-force failed login alerts were triggered, consider forcing password rotation or updating credential lockout strategies.
            </li>
        </ul>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->user_id): ?>
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <a href="<?php echo e(url(config('auditify.route_prefix', 'auditify') . '/action-logs?user_id=' . $log->user_id)); ?>" class="btn btn-secondary" style="font-size: 12px;">
                    🔍 Investigate User's Action Logs
                </a>
                <a href="<?php echo e(url(config('auditify.route_prefix', 'auditify') . '/activity-logs?user_id=' . $log->user_id)); ?>" class="btn btn-secondary" style="font-size: 12px;">
                    👣 Investigate User's Activity Logs
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auditify::layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Highblossom\vendor\arpanihan\auditify\resources\views\security-logs\show.blade.php ENDPATH**/ ?>