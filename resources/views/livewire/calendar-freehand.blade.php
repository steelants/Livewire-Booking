<div>
    <div class="calendar calendar-freehand">
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
                                    @if ($slot['status'] == 'available')
                                        <div class="calendar-area-freehand">
                                            <div class="calendar-btn-freehand booking-bg-active" style="display: none;">
                                                {{ $reserveText }}</div>
                                        </div>
                                    @elseif($slot['status'] == 'partially-avilable')
                                        <button
                                            class="calendar-btn booking-bg-{{ $slot['status'] }} calendar-btn-hover"
                                            title="{{ $slot['timeFrom'] }} - {{ $slot['timeTo'] }}"
                                            type="button"
                                            wire:click="$dispatch('openModal', {{ json_encode(['livewireComponents' => $modalComponent, 'title' => __($modalTitle), 'parameters' => ['slot' => $slot['id']], 'static' => true]) }})"
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
        <div class="d-inline-block me-2 border" style="width: 1.5em; height: 1.5rem;"></div>
        <div>{{ __('Volno, možno rezervovat (volný 1 nebo více kurtů)') }}</div>
    </div>
    <div class="d-flex align-items-center mb-2">
        <div class="d-inline-block me-2 booking-bg-partially-avilable border" style="width: 1.5em; height: 1.5rem;">
        </div>
        <div>{{ __('Hrajete, možnost další rezervace (je volný jeden nebo více kurtů)') }}</div>
    </div>
    <div class="d-flex align-items-center mb-2">
        <div class="d-inline-block me-2 booking-bg-reserved border" style="width: 1.5em; height: 1.5rem;">
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

            var minuteSize = 1.3333;
            var slotSize = {{ $freehandStepSize }} * minuteSize;

            console.log('slotSize:', slotSize);

            var isSelecting = false;
            var selectingTop = 0;
            var selectingHeight = 0;
            var selectedSlot = '';
            var $activeBtn;

            $('body').on('mouseenter', '.calendar-area-freehand', function(e) {
                let btn = $(this).find('.calendar-btn-freehand');
                if (!isSelecting && !btn.hasClass('calendar-btn-freehand-active')) {
                    positionFreehand(btn, e);
                    resizeFreehand(btn, e);
                }
            });

            $('body').on('mouseleave', '.calendar-area-freehand', function(e) {
                if (!isSelecting) {
                    $(this).find('.calendar-btn-freehand').not('.calendar-btn-freehand-active').hide();
                }
            });

            // move / resize
            $('body').on('mousemove', '.calendar-area-freehand', function(e) {
                let btn = $(this).find('.calendar-btn-freehand');
                if (!btn.hasClass('calendar-btn-freehand-active')) {
                    if (!isSelecting) {
                        positionFreehand(btn, e);
                    } else {
                        resizeFreehand(btn, e);
                        btn.show();
                    }
                }
            });

            // start selecting
            $('body').on('mousedown', '.calendar-area-freehand', function(e) {
                isSelecting = true;
                $('body').css('user-select', 'none');
                selectedSlot = $(this).closest('.calendar-item').attr('data-slot');
                $activeBtn = $(this).find('.calendar-btn-freehand');
                // console.log('selectedSlot', selectedSlot);
            });

            // stop selecting
            $('body').on('mouseup', '.calendar-freehand', function(e) {
                if (isSelecting) {
                    isSelecting = false;
                    $activeBtn.addClass('calendar-btn-freehand-active');
                    $('body').css('user-select', '');

                    let data = {
                        slot: selectedSlot,
                        start: pixelToMinute(selectingTop),
                        length: pixelToMinute(selectingHeight),
                    }

                    Livewire.dispatch('openModal', {
                        livewireComponents: {!! json_encode($modalComponent) !!},
                        title: {!! json_encode($modalTitle) !!},
                        parameters: data
                    })

                    console.log(data);
                }
            });


            function positionFreehand($btn, e) {
                let rect = e.target.getBoundingClientRect();
                let top = e.clientY - rect.top;
                selectingTop = slotSize * Math.floor(top / slotSize);
                // console.log('selectingTop:', selectingTop);
                $btn.css('top', selectingTop);
            }

            function resizeFreehand($btn, e) {
                let rect = e.target.getBoundingClientRect();
                let top = e.clientY - rect.top;
                let height = slotSize + slotSize * Math.floor(top / slotSize) - selectingTop;
                selectingHeight = Math.max(height, slotSize);
                // console.log('height:', height, e.clientY, selectingTop);
                $btn.css('height', selectingHeight - 4);
            }

            function minuteToPixels(min) {
                return min * minuteSize;
            }

            function pixelToMinute(px) {
                return Math.round(px / minuteSize);
            }
        </script>
    @endscript
</div>
