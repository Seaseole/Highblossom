@blaze(fold: true, unsafe: ['logo:dark'])

@php $logoDark ??= $attributes->pluck('logo:dark'); @endphp

@props([
    'name' => null,
    'logo' => null,
    'logoDark' => null,
    'alt' => null,
    'href' => '/',
])

@php
    $classes = Flux::classes()
        ->add('h-10 flex items-center px-2 in-data-flux-sidebar-collapsed-desktop:w-10 in-data-flux-sidebar-collapsed-desktop:px-0 in-data-flux-sidebar-collapsed-desktop:justify-center')
        ->add('in-data-flux-sidebar-collapsed-desktop:in-data-flux-sidebar-active:absolute')
        ->add('in-data-flux-sidebar-collapsed-desktop:in-data-flux-sidebar-active:opacity-0');

    $textClasses = Flux::classes()
        ->add('text-sm font-medium truncate [:where(&)]:text-zinc-800 dark:[:where(&)]:text-zinc-100');
@endphp

<?php
use Illuminate\View\ComponentSlot;

if ($name) { ?>
<a href="{{ $href }}" {{ $attributes->class([ $classes, 'gap-2' ]) }} data-flux-sidebar-brand>
    <?php if ($logo instanceof ComponentSlot) { ?>
    <div {{ $logo->attributes->class('flex items-center justify-center [:where(&)]:h-6 [:where(&)]:min-w-6 [:where(&)]:rounded-sm overflow-hidden shrink-0') }}>
        {{ $logo }}
    </div>
    <?php } else { ?>
    <div class="flex h-6 min-w-6 shrink-0 items-center justify-center overflow-hidden rounded-sm">
        <?php if ($logoDark) { ?>
        <img src="{{ $logo }}" alt="{{ $alt }}" class="h-6 min-w-6 dark:hidden" />
        <img src="{{ $logoDark }}" alt="{{ $alt }}" class="hidden h-6 min-w-6 dark:block" />
        <?php } elseif ($logo) { ?>
        <img src="{{ $logo }}" alt="{{ $alt }}" class="h-6 min-w-6" />
        <?php } else { ?>
        {{ $slot }}
        <?php } ?>
    </div>
    <?php } ?>

    <div class="{{ $textClasses }} in-data-flux-sidebar-collapsed-desktop:hidden">{{ $name }}</div>
</a>
<?php } else { ?>
<a href="{{ $href }}" {{ $attributes->class($classes) }} data-flux-sidebar-brand>
    <?php if ($logo instanceof ComponentSlot) { ?>
    <div {{ $logo->attributes->class('flex items-center justify-center [:where(&)]:h-6 [:where(&)]:min-w-6 [:where(&)]:rounded-sm overflow-hidden shrink-0') }}>
        {{ $logo }}
    </div>
    <?php } else { ?>
    <div class="flex h-6 shrink-0 items-center justify-center overflow-hidden rounded-sm">
        <?php if ($logoDark) { ?>
        <img src="{{ $logo }}" alt="{{ $alt }}" class="h-6 dark:hidden" />
        <img src="{{ $logoDark }}" alt="{{ $alt }}" class="hidden h-6 dark:block" />
        <?php } elseif ($logo) { ?>
        <img src="{{ $logo }}" alt="{{ $alt }}" class="h-6" />
        <?php } else { ?>
        {{ $slot }}
        <?php } ?>
    </div>
    <?php } ?>
</a>
<?php } ?>
