<?php $__env->startComponent('mail::message'); ?>
# Booking Request Received

Hi <?php echo e($booking->client_name); ?>,

Thank you for choosing Highblossom. We have received your booking request for the following vehicle:

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

We have received your request. Our staff will review your booking and send you a confirmation email once it has been approved and added to our inspection list.

Thanks,<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\emails\bookings\confirmation.blade.php ENDPATH**/ ?>