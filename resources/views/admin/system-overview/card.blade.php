@php
    $recalculate = $recalculate ?? false;
    $action = $action ?? null;
@endphp

<section
    id="system-overview-{{ $section->key }}"
    class="hb-desk-card"
>
    <header class="hb-desk-card__head">
        <div class="flex min-w-0 items-start gap-2">
            <x-ui.icon :name="$section->icon" size="xs" class="mt-1 shrink-0 text-gray-400 dark:text-gray-500" />
            <div class="min-w-0">
                <h2 class="hb-desk-card__title">{{ $section->label }}</h2>
                <p class="hb-desk-card__desc">{{ $section->description }}</p>
            </div>
        </div>
        {{-- The word is the record; the mark beside it is only where the eye finds
             the word. A colour alone would be a second, quieter copy of the same
             verdict that greyscale and screen readers never receive. --}}
        <p class="hb-desk-card__verdict hb-desk-card__verdict--{{ $section->status->value }}">
            <span class="hb-mark" aria-hidden="true"></span>{{ ucfirst($section->status->value) }}
        </p>
    </header>

    <dl>
        @foreach ($section->items as $item)
            {{-- A neutral line has no severity at all, and `@class` evaluates every
                 key in its array whether the condition passes or not, so the tone is
                 resolved once here rather than inside the attribute. --}}
            @php $tone = $item->status?->value; @endphp
            <div @class([
                'hb-desk-row',
                'hb-desk-row--'.$tone => $tone !== null,
            ])>
                <dt>{{ $item->label }}</dt>
                <dd>
                    <div class="flex items-center justify-end gap-2">
                        @if ($tone !== null)
                            {{-- The dot is where the eye finds the verdict; the word is the verdict. --}}
                            <span class="hb-dot hb-dot--{{ $tone }}" aria-hidden="true"></span>
                            <span class="sr-only">{{ ucfirst($tone) }}</span>
                        @endif

                        @if ($item->value === null)
                            <span class="hb-muted">&mdash;</span>
                        @elseif ($item->route !== null && Route::has($item->route))
                            <a
                                class="hb-link hb-num"
                                href="{{ route($item->route) }}"
                            >{{ $item->value }}</a>
                        @else
                            {{-- Mono is for what was measured: a number, a version, a path.
                                 A value that is a whole sentence is set in the text face. --}}
                            <span @class(['hb-num' => mb_strlen((string) $item->value) <= 24])>{{ $item->value }}</span>
                        @endif
                    </div>

                    @if ($item->hint !== null)
                        <span class="hb-desk-row__hint">{{ $item->hint }}</span>
                    @endif
                </dd>
            </div>
        @endforeach
    </dl>

    <footer class="hb-desk-card__foot">
        <span>
            @if ($section->warmAt === null)
                {{ __('messages.system_overview_never_measured') }}
            @else
                {{ __('messages.system_overview_measured', [
                    'ms' => $section->elapsedMs,
                    'when' => \Illuminate\Support\Carbon::createFromTimestamp($section->warmAt)->diffForHumans(),
                ]) }}
            @endif
        </span>

        @if (! empty($recalculate))
            <button
                type="button"
                wire:click="measure"
                wire:loading.attr="disabled"
                wire:target="measure"
                class="hb-btn hb-btn--small"
            >
                <span wire:loading.remove wire:target="measure">{{ __('messages.system_overview_measure_now') }}</span>
                <span wire:loading wire:target="measure">{{ __('messages.system_overview_measuring') }}</span>
            </button>
        @endif

        @if ($action !== null)
            <form method="POST" action="{{ route($action['route']) }}" onsubmit="return confirm('{{ $action['confirm'] }}');">
                @csrf
                <button type="submit" class="hb-btn hb-btn--small">{{ $action['label'] }}</button>
            </form>
        @endif
    </footer>
</section>
</section>
