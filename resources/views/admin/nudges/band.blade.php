{{-- Rule-based nudges, shared by the day desk and the system overview. Both
     pages speak hb-*, so one band fits both. $nudges is already permission
     filtered and capped by NudgeService::visible(); the flag check here keeps
     the band invisible even if a caller forgets. --}}
@if (config('ai.enabled') && $nudges->isNotEmpty())
    <section class="hb-band" aria-labelledby="nudges-title" data-dash-reveal>
        <div class="hb-head">
            <div class="hb-head-text">
                <h2 class="hb-h2" id="nudges-title">{{ __('admin-nudges.title') }}</h2>
                <p class="hb-hint">{{ __('admin-nudges.hint') }}</p>
            </div>
            <p class="hb-count">
                <span class="hb-num">{{ $nudges->count() }}</span>
            </p>
        </div>

        <ol class="hb-queue hb-nudges">
            @foreach ($nudges as $nudge)
                <li class="hb-queue-row hb-tone--{{ $nudge->severity === \App\Models\Nudge::SEVERITY_WARN ? 'late' : 'waiting' }}">
                    <div class="hb-nudge-main">
                        <a class="hb-nudge-link" href="{{ $nudge->action_url }}">
                            <span class="hb-nudge-message">{{ $nudge->message }}</span>
                            <span class="hb-nudge-action">
                                {{ $nudge->action_label }}
                                <span class="hb-queue-go" aria-hidden="true"></span>
                            </span>
                        </a>
                        <div class="hb-nudge-actions">
                            <form method="POST" action="{{ route('admin.nudges.snooze', $nudge) }}">
                                @csrf
                                <button type="submit" class="hb-nudge-btn">{{ __('admin-nudges.snooze') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.nudges.dismiss', $nudge) }}">
                                @csrf
                                <button type="submit" class="hb-nudge-btn">{{ __('admin-nudges.dismiss') }}</button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>
@endif