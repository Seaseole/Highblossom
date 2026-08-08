<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body class="min-h-screen bg-gray-100">
    <header class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <div class="flex flex-shrink-0 items-center">
                        <h1 class="text-xl font-bold text-gray-800">Highblossom</h1>
                    </div>
                    <nav class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a
                            href="<?php echo e(route('dashboard')); ?>"
                            class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'); ?>"
                        >
                            Dashboard
                        </a>
                    </nav>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button
                            type="submit"
                            class="ml-3 inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8"><?php echo e($slot); ?></main>
</body>
</html>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\layouts\app\header.blade.php ENDPATH**/ ?>