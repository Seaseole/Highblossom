<?php $__env->startComponent('mail::message'); ?>
# Booking Confirmed

Hi <?php echo e($booking->client_name); ?>,

Great news! Your booking has been confirmed and added to our inspection list.

**Vehicle Details:**
<?php echo e($booking->vehicle_details); ?>


**Scheduled Date:**
<?php echo e($booking->scheduled_at ? $booking->scheduled_at->format('M d, Y H:i') : 'To be confirmed'); ?>


**Location:**
<?php echo e($booking->location === 'mobile' ? 'Mobile Service' : 'Workshop'); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->location === 'mobile' && $booking->client_address): ?>
**Service Address:**
<?php echo e($booking->client_address); ?>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

Our team looks forward to serving you. If you need to make any changes, please reply to this email or contact our support team.

Thanks,<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\emails\bookings\confirmed.blade.php ENDPATH**/ ?>