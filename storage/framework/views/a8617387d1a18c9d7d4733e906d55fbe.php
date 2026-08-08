<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo e($subject); ?></title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; color: #1f2937; line-height: 1.5; padding: 24px; background: #f9fafb;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px;">
        <h2 style="margin: 0 0 8px 0; color: #111827;"><?php echo e($subject); ?></h2>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($sourceName)): ?>
            <p style="margin: 0 0 4px 0; font-size: 13px; color: #4b5563;">
                <strong>Site:</strong> <?php echo e($sourceName); ?>

            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <p style="margin: 0 0 16px 0; font-size: 13px; color: #6b7280;">
            <strong>Trigger:</strong> <?php echo e($triggerLabel); ?> &middot;
            <strong>Fired at:</strong> <?php echo e($triggeredAt->format('Y-m-d H:i:s T')); ?>

        </p>

        <p style="margin: 0 0 20px 0; font-size: 15px;"><?php echo e($body); ?></p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($recentFailures)): ?>
            <?php
                $totalCount = $context['count'] ?? null;
                $shown = count($recentFailures);
                $moreCount = is_int($totalCount) && $totalCount > $shown ? $totalCount - $shown : 0;
            ?>
            <h3 style="margin: 24px 0 8px 0; font-size: 14px; color: #111827; text-transform: uppercase; letter-spacing: 0.05em;">
                Recent failures
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moreCount > 0): ?>
                    <span style="color: #9ca3af; font-size: 11px; font-weight: normal; text-transform: none; letter-spacing: 0;">
                        (showing <?php echo e($shown); ?> of <?php echo e($totalCount); ?>)
                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 13px;">
                <thead>
                    <tr style="background: #f3f4f6; text-align: left;">
                        <th style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #4b5563;">Job</th>
                        <th style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #4b5563;">Attempt</th>
                        <th style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #4b5563;">Exception</th>
                        <th style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #4b5563;">When</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentFailures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sample): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php ($detailUrl = $detailUrlBuilder($sample)); ?>
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #f3f4f6; font-family: monospace;">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($detailUrl): ?>
                                    <a href="<?php echo e($detailUrl); ?>" style="color: #4f46e5; text-decoration: none;">
                                        <?php echo e($sample->shortClass()); ?>

                                    </a>
                                <?php else: ?>
                                    <?php echo e($sample->shortClass()); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td style="padding: 8px; border-bottom: 1px solid #f3f4f6; color: #6b7280;">
                                #<?php echo e($sample->attempt); ?>

                            </td>
                            <td style="padding: 8px; border-bottom: 1px solid #f3f4f6; color: #374151; max-width: 280px; overflow-wrap: anywhere;">
                                <?php echo e($sample->shortException() ?? '—'); ?>

                            </td>
                            <td style="padding: 8px; border-bottom: 1px solid #f3f4f6; color: #6b7280; white-space: nowrap;">
                                <?php echo e($sample->failedAt->format('H:i:s')); ?>

                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($moreCount > 0 && $dashboardUrl): ?>
                <p style="margin: -8px 0 16px 0; font-size: 12px; color: #6b7280; text-align: right;">
                    <a href="<?php echo e($dashboardUrl); ?>" style="color: #4f46e5; text-decoration: none;">
                        + <?php echo e($moreCount); ?> more — open dashboard →
                    </a>
                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dashboardUrl): ?>
            <p style="margin: 24px 0 8px 0;">
                <a href="<?php echo e($dashboardUrl); ?>" style="display: inline-block; background: #4f46e5; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                    Open dashboard
                </a>
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($context)): ?>
            <details style="margin-top: 24px;">
                <summary style="cursor: pointer; color: #6b7280; font-size: 12px;">Full context</summary>
                <table style="border-collapse: collapse; margin-top: 8px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $context; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td style="padding: 4px 12px 4px 0; color: #6b7280; font-size: 12px; text-transform: uppercase;">
                                <?php echo e($key); ?>

                            </td>
                            <td style="padding: 4px 0; font-family: monospace; font-size: 12px;">
                                <?php echo e(is_scalar($value) ? $value : json_encode($value)); ?>

                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </table>
            </details>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <p style="margin: 32px 0 0 0; font-size: 11px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 12px;">
            Generated by jobs-monitor. Adjust thresholds or channels in <code>config/jobs-monitor.php</code>.
        </p>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\mail\alert.blade.php ENDPATH**/ ?>