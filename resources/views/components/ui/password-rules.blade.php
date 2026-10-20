{{--
    Live password requirement checklist.

    The list is the contract: resources/js/password-rules.js finds the input by the id in
    data-pw-for and evaluates each item from its own data attributes, so there is no
    second copy of the limits to keep in sync. Every label comes from
    PasswordPolicy::checklist(), which builds them out of the same sentences the
    validator returns on failure — the hint and the error cannot disagree.

    Works with and without Alpine, because the auth screens boot no Alpine at all.
--}}
@props([
    'input' => null,
])

@if ($input)
    @php
        $items = app(\App\Services\PasswordPolicy::class)->checklist();
    @endphp

    <ul
        id="{{ $input }}-rules"
        data-pw-for="{{ $input }}"
        class="mt-2 space-y-1"
        aria-label="Password requirements"
    >
        @foreach ($items as $item)
            <li
                data-pw-rule="{{ $item['id'] }}"
                @if ($item['min']) data-min="{{ $item['min'] }}" @endif
                @if ($item['max']) data-max="{{ $item['max'] }}" @endif
                data-met="false"
                class="flex items-center gap-1.5 text-ui-xs text-ui-muted transition-colors data-[met=true]:text-ui-success dark:data-[met=true]:text-emerald-400"
            >
                <x-ui.icon name="x-mark" size="xs" data-pw-marker-off />
                <x-ui.icon name="check" size="xs" data-pw-marker-on class="hidden" />
                <span>{{ $item['label'] }}</span>
            </li>
        @endforeach
    </ul>
@endif
