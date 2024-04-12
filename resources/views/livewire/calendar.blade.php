<div>
    <div class="calendar calendar-classic">
        <div class="calendar-area">
            <div class="calendar-times">
                @foreach ($times as $time)
                    <div class="calendar-time @if ($time == $selectedTime) calendar-time-active @endif">
                        {{ $time }}</div>
                @endforeach
            </div>
            <div class="calendar-days">
                <div class="calendar-gridlines">
                    @foreach ($times as $time)
                        <div class="calendar-gridline"></div>
                    @endforeach
                </div>
                @foreach ($days as $day)
                    <div class="calendar-day @if ($day == $selectedDay) calendar-day-active @endif"
                        wire:key="day_{{ $day }}"
                    >
                        <div class="calendar-day-name">{{ Illuminate\Support\Carbon::parse($day)->format('D d. m. Y') }}</div>
                        <div class="calendar-items">
                            @foreach ($slots[$day] ?? [] as $slotKey => $slot)
                                <div
                                    class="calendar-item calendar-item-{{ $slot['status'] }}"
                                    wire:key="slot_{{ $slotKey }}"
                                    data-slot="{{ $slotKey }}"
                                    style="--duration: {{ $slot['duration'] }};--offset: {{ $slot['offset'] }};"
                                >
                                    @if ($slot['status'] == 'available' || $slot['status'] == 'partially-avilable')
                                        <button
                                            class="calendar-btn booking-bg-{{ $slot['status'] }} calendar-btn-hover"
                                            title="{{ $slot['timeFrom'] }} - {{ $slot['timeTo'] }}"
                                            type="button"
                                            wire:click="$dispatch('openModal', {{ json_encode(['livewireComponents' => $modalComponent, 'title' => __($modalTitle), 'parameters' => ['slot' => $slotKey]]) }})"
                                        >
                                            <div class="calendar-btn-text">{{ $slot['text'] }}</div>
                                            <div class="calendar-btn-text-hover">{{ __($reserveText) }}</div>
                                        </button>
                                    @else
                                        <div class="calendar-btn booking-bg-{{ $slot['status'] }}"
                                            title="{{ $slot['timeFrom'] }} - {{ $slot['timeTo'] }}"
                                        >
                                            <div class="calendar-btn-text">{{ $slot['text'] }}</div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <h6 class="mt-3">{{ __('Legenda') }}</h6>
    <div class="d-flex align-items-center mb-2">
        <div class="d-inline-block me-2 booking-bg-available border" style="width: 1.5em; height: 1.5rem;">
        </div>
        <div>{{ __('Volno, možno rezervovat (volný 1 nebo více kurtů)') }}</div>
    </div>
    <div class="d-flex align-items-center mb-2">
        <div class="d-inline-block me-2 booking-bg-partially-avilable border" style="width: 1.5em; height: 1.5rem;">
        </div>
        <div>{{ __('Hrajete, možnost další rezervace (je volný jeden nebo více kurtů)') }}</div>
    </div>
    <div class="d-flex align-items-center mb-2">
        <div class="d-inline-block me-2 border booking-bg-reserved" style="width: 1.5em; height: 1.5rem;">
        </div>
        <div>{{ __('Hrajete, nelze rezervovat (již není volný žádný kurt)') }}</div>
    </div>
    <div class="d-flex align-items-center mb-2">
        <div class="d-inline-block booking-bg-disabled me-2 border" style="width: 1.5em; height: 1.5rem;"></div>
        <div>{{ __('Nelze rezervovat') }}</div>
    </div>

    @script
        <script>
            initCalendarTooltip();
        </script>
    @endscript
</div>
