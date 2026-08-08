<?php
    $pollId = $poll_id ?? null;
    $question = $question ?? '';
    $options = $options ?? [];
    $allowMultiple = $allow_multiple ?? false;
    $showResults = $show_results ?? true;
    $uniqueId = 'cb-poll-'.($pollId ?? uniqid());

    // Use session check for vote status
    $hasVoted = $pollId ? session()->has("poll_voted_{$pollId}") : false;

    // Fetch results if they should be shown
    $results = [];
    if ($pollId) {
        $poll = \App\Models\Poll::with('votes')->find($pollId);
        if ($poll && ($poll->show_results || $hasVoted)) {
            $results = $poll->results;
        }
    }
?>

<div
    class="cb-poll rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5"
    x-data="{
        selectedOptions: [],
        selectedOption: null,
        allowMultiple: <?php echo e($allowMultiple ? 'true' : 'false'); ?>,
        hasVoted: <?php echo e($hasVoted ? 'true' : 'false'); ?>,
        results: <?php echo \Illuminate\Support\Js::from($results)->toHtml() ?>,
        isVoting: false,
        error: null,

        get hasSelection() {
            return this.allowMultiple
                ? this.selectedOptions.length > 0
                : this.selectedOption !== null;
        },

        async vote() {
            if (! this.hasSelection) return;

            this.isVoting = true;
            this.error = null;

            try {
                const url = '<?php echo e(route('poll.vote', ['poll' => $pollId ?? 0])); ?>';
                const opts = this.allowMultiple
                    ? this.selectedOptions.map(Number)
                    : [Number(this.selectedOption)];

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        options: opts
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    this.results = data.results;
                    this.hasVoted = true;
                } else {
                    this.error = data.error || 'Something went wrong';
                    if (data.results) {
                        this.results = data.results;
                        this.hasVoted = true;
                    }
                }
            } catch (e) {
                this.error = 'Failed to submit vote. Please try again.';
            } finally {
                this.isVoting = false;
            }
        }
     }"
>
    <h3 class="mb-6 text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($question); ?></h3>

    <!-- Form Section -->
    <div class="mb-6 space-y-4" x-show="! hasVoted">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <label class="group flex cursor-pointer items-center gap-3 rounded-2xl border border-gray-100 bg-gray-50 p-4 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white">
                <input
                    type="<?php echo e($allowMultiple ? 'checkbox' : 'radio'); ?>"
                    name="<?php echo e($uniqueId); ?>"
                    value="<?php echo e($index); ?>"
                    x-model="<?php echo e($allowMultiple ? 'selectedOptions' : 'selectedOption'); ?>"
                    class="h-5 w-5 rounded-full border-gray-300 text-gray-900 transition-all focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                />
                <span class="font-medium text-gray-700 dark:text-gray-300"><?php echo e($option); ?></span>
            </label>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <div
        x-show="error"
        x-text="error"
        class="mb-4 rounded-xl bg-red-50 p-3 text-sm text-red-500 dark:bg-red-900/20"
    ></div>

    <!-- Vote Button -->
    <button
        type="button"
        @click="vote"
        :disabled="! hasSelection || isVoting"
        x-show="! hasVoted"
        class="flex w-full items-center justify-center gap-3 rounded-2xl bg-gray-900 py-3 font-semibold text-white transition-all hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-white dark:text-gray-900"
    >
        <span
            x-show="isVoting"
            x-cloak
            class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"
        ></span>
        <span x-show="isVoting" x-cloak>Submitting...</span>
        <span x-show="! isVoting">Submit Vote</span>
    </button>

    <!-- Results Section -->
    <div class="space-y-6" x-show="hasVoted && <?php echo e($showResults ? 'true' : 'false'); ?>" x-transition>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium text-gray-900 dark:text-white"><?php echo e($option); ?></span>
                    <span
                        class="font-mono text-gray-500 dark:text-gray-400"
                        x-text="(results[<?php echo e($index); ?>] || 0) + '%'"
                    ></span>
                </div>
                <div class="h-3 overflow-hidden rounded-full bg-gray-100 dark:bg-white/10">
                    <div
                        class="h-full bg-gray-900 transition-all duration-1000 ease-out dark:bg-white"
                        :style="{ width: (results[<?php echo e($index); ?>] || 0) + '%' }"
                    ></div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <p class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
            <?php echo e($ showResults ? 'Thank you for voting!' : 'Thank you for your vote!'); ?>

        </p>
    </div>
</div>
<?php /**PATH C:\laragon\www\Highblossom\packages\ContentBlocks\resources\views\poll.blade.php ENDPATH**/ ?>