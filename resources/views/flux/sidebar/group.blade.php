@blaze(fold: true, unsafe: ['icon:trailing', 'icon:variant'])

@php $iconTrailing ??= $attributes->pluck('icon:trailing'); @endphp
@php $iconVariant ??= $attributes->pluck('icon:variant'); @endphp

@props([
    'iconVariant' => 'outline',
    'iconTrailing' => null,
    'expandable' => false,
    'expanded' => true,
    'heading' => null,
    'icon' => null,
])

<?php if ($expandable && $heading) { ?>
<?php if ($icon) { ?>
<ui-disclosure
    {{ $attributes->class('group/disclosure in-data-flux-sidebar-collapsed-desktop:hidden') }}
    @if ($expanded === true) open @endif
    data-flux-sidebar-group
>
    <button
        type="button"
        class="group/disclosure-button my-px flex h-8 w-full items-center rounded-lg border-1 border-transparent text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 in-data-flux-sidebar-on-mobile:h-10 dark:text-white/80 dark:hover:bg-white/[7%] dark:hover:text-white"
    >
        <div class="px-3">
            <?php if (is_string($icon) && $icon !== '') { ?>
            <flux:icon :icon="$icon" :variant="$iconVariant" class="size-4" />
            <?php } else { ?>
            {{ $icon }}
            <?php } ?>
        </div>

        <span class="flex-1 text-left text-sm leading-none font-medium rtl:text-right">{{ $heading }}</span>

        <div class="ps-3 pe-2.5">
            <flux:icon.chevron-down class="hidden size-3! group-data-open/disclosure-button:block" />
            <flux:icon.chevron-right class="block size-3! group-data-open/disclosure-button:hidden rtl:rotate-180" />
        </div>
    </button>

    <div class="relative hidden ps-7 data-open:block" @if ($expanded === true) data-open @endif>
        <div class="absolute inset-y-[3px] start-0 ms-5 w-px bg-zinc-200 dark:bg-white/30"></div>

        <div class="flex flex-col">{{ $slot }}</div>
    </div>
</ui-disclosure>

<flux:dropdown
    hover
    class="not-in-data-flux-sidebar-collapsed-desktop:hidden in-data-flux-sidebar-on-mobile:hidden"
    position="right"
    align="start"
    data-flux-sidebar-group-dropdown
>
    <button
        type="button"
        class="group/disclosure-button my-px flex h-8 w-full items-center gap-3 rounded-lg border-1 border-transparent px-3 text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 in-data-flux-menu:px-2 in-data-flux-menu:text-zinc-800 in-data-flux-menu:hover:bg-zinc-50 in-data-flux-sidebar-collapsed-desktop:not-in-data-flux-menu:w-10 in-data-flux-sidebar-collapsed-desktop:not-in-data-flux-menu:justify-center dark:text-white/80 dark:hover:bg-white/[7%] dark:hover:text-white in-data-flux-menu:dark:text-white dark:in-data-flux-menu:hover:bg-zinc-600"
    >
        <?php if ($icon) { ?>
        <div class="relative">
            <?php if (is_string($icon) && $icon !== '') { ?>
            <flux:icon
                :icon="$icon"
                :variant="$iconVariant"
                class="in-data-flux-menu:[[data-flux-sidebar-group-dropdown]>button:hover_&]:text-current size-4 in-data-flux-menu:text-zinc-400 in-data-flux-menu:dark:text-white/80"
            />
            <?php } else { ?>
            {{ $icon }}
            <?php } ?>
        </div>
        <?php } ?>

        <span class="hidden flex-1 text-start text-sm leading-none font-medium text-zinc-800 in-data-flux-menu:block dark:text-white">{{ $heading }}</span>

        <div class="hidden in-data-flux-menu:block">
            <flux:icon.chevron-right
                :variant="$iconVariant"
                class="[[data-flux-sidebar-group-dropdown]>button:hover_&]:text-current ms-auto size-4 text-zinc-400 rtl:hidden"
            />
            <flux:icon.chevron-left
                :variant="$iconVariant"
                class="[[data-flux-sidebar-group-dropdown]>button:hover_&]:text-current ms-auto hidden size-4 text-zinc-400 rtl:inline"
            />
        </div>
    </button>

    <flux:menu>
        <flux:menu.group :$heading> {{ $slot }} </flux:menu.group>
    </flux:menu>
</flux:dropdown>
<?php } else { ?>
<ui-disclosure
    {{ $attributes->class('group/disclosure in-data-flux-sidebar-collapsed-desktop:hidden') }}
    @if ($expanded === true) open @endif
    data-flux-sidebar-group
>
    <button
        type="button"
        class="group/disclosure-button my-px flex h-8 w-full items-center rounded-lg border-1 border-transparent text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 in-data-flux-sidebar-on-mobile:h-10 dark:text-white/80 dark:hover:bg-white/[7%] dark:hover:text-white"
    >
        <div class="ps-3.5 pe-3.5">
            <flux:icon.chevron-down class="hidden size-3! group-data-open/disclosure-button:block" />
            <flux:icon.chevron-right class="block size-3! group-data-open/disclosure-button:hidden rtl:rotate-180" />
        </div>

        <span class="text-sm leading-none font-medium">{{ $heading }}</span>
    </button>

    <div class="relative hidden ps-7 data-open:block" @if ($expanded === true) data-open @endif>
        <div class="absolute inset-y-[3px] start-0 ms-5 w-px bg-zinc-200 dark:bg-white/30"></div>

        <div class="flex flex-col">{{ $slot }}</div>
    </div>
</ui-disclosure>
<?php } ?>

<?php } elseif ($heading) { ?>
<div {{ $attributes->class('flex flex-col in-data-flux-sidebar-collapsed-desktop:hidden') }} data-flux-sidebar-group>
    <div class="px-3 py-2">
        <div class="text-sm leading-none font-medium text-zinc-400">{{ $heading }}</div>
    </div>

    <div class="flex flex-col">{{ $slot }}</div>
</div>
<?php } else { ?>
<div {{ $attributes->class('flex flex-col in-data-flux-sidebar-collapsed-desktop:hidden') }} data-flux-sidebar-group>
    {{ $slot }}
</div>
<?php } ?>
