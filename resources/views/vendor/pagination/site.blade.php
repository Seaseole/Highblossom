{{--
    Public-site paginator. The framework view is light-themed and renders bare
    <a href>, so page turns were full reloads in the middle of an otherwise
    instant blog index. Same-origin GET links, so wire:navigate is safe here.
--}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <ul class="flex flex-wrap items-center justify-center gap-2">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="inline-flex min-h-[44px] items-center rounded-full border border-white/5 px-4 text-sm text-[#52525B]" aria-disabled="true">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <a
                        href="{{ $paginator->previousPageUrl() }}"
                        wire:navigate
                        rel="prev"
                        class="inline-flex min-h-[44px] items-center rounded-full border border-white/10 px-4 text-sm text-[#A1A1AA] transition-colors hover:border-[#DC2626]/40 hover:text-[#FAFAFA]"
                    >
                        {!! __('pagination.previous') !!}
                    </a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span class="inline-flex min-h-[44px] items-center px-2 text-sm text-[#52525B]">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span
                                    aria-current="page"
                                    class="inline-flex min-h-[44px] items-center rounded-full bg-[#DC2626] px-4 text-sm font-semibold text-white"
                                >{{ $page }}</span>
                            @else
                                <a
                                    href="{{ $url }}"
                                    wire:navigate
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                    class="inline-flex min-h-[44px] items-center rounded-full border border-white/10 px-4 text-sm text-[#A1A1AA] transition-colors hover:border-[#DC2626]/40 hover:text-[#FAFAFA]"
                                >{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a
                        href="{{ $paginator->nextPageUrl() }}"
                        wire:navigate
                        rel="next"
                        class="inline-flex min-h-[44px] items-center rounded-full border border-white/10 px-4 text-sm text-[#A1A1AA] transition-colors hover:border-[#DC2626]/40 hover:text-[#FAFAFA]"
                    >
                        {!! __('pagination.next') !!}
                    </a>
                @else
                    <span class="inline-flex min-h-[44px] items-center rounded-full border border-white/5 px-4 text-sm text-[#52525B]" aria-disabled="true">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
