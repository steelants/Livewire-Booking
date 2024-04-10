<?php

namespace SteelAnts\Booking\Livewire;

use Livewire\Component;

class Calendar extends Component
{

    protected $listeners = [
        'closeModal' => 'refreshCalendar'
    ];

    protected int $hourFrom = 6;
    protected int $hourTo = 20;

    public ?string $selectedTime;
    public ?string $selectedDay;
    public ?string $dateFrom;
    public ?string $dateTo;
    public string $renderType = 'classic';
    public int $freehandStepSize = 5;

    public string $reserveText = 'Rezervovat';
    public string $modalComponent = 'booking-form';
    public string $modalTitle = 'Rezervovat termín';

    public array $times = [];
    public array $days = [];
    public array $slots = [];

    // public function mount()
    // {
    //     $this->renderType = 'freehand';
    //     $this->dateFrom = date('Y-m-d', strtotime('today'));
    //     $this->dateTo = date('Y-m-d', strtotime('+7 days'));
    //     $this->selectedDay = date('Y-m-d', strtotime('today'));

    //     // required action
    //     $this->init();
    // }

    protected function init()
    {
        if(empty($this->dateFrom)){
            $this->dateFrom = date('Y-m-d', strtotime('today'));
        }
        if(empty($this->dateTo)){
            $this->dateTo = date('Y-m-d', strtotime('+7 days'));
        }
        if(empty($this->selectedDay)){
            $this->selectedDay = date('Y-m-d', strtotime('today'));
        }

        $this->initTimes();
        $this->initSlots();
    }

    public function refreshCalendar()
    {
        $this->initSlots();
        $this->dispatch('initCalendarTooltip');
    }

    public function render()
    {
        if ($this->renderType == 'freehand') {
            return view('booking::calendar-freehand');
        }
        return view('booking::calendar');
    }

    protected function generateTimes($fromHour, $toHour, $stepSize)
    {
        $times = [];

        for ($i = $fromHour * 60; $i <= $toHour * 60; $i += $stepSize) {
            $times[] = sprintf('%02d', $i / 60) . ':' . sprintf('%02d', $i % 60);
        }

        return $times;
    }

    protected function generateDays()
    {
        $days = [];
        for ($d = strtotime($this->dateFrom); $d < strtotime($this->dateTo); $d += (24 * 60 * 60)) {
            $days[] = date('Y-m-d', $d);
        }
        return $days;
    }

    protected function initTimes()
    {
        $this->times = $this->generateTimes($this->hourFrom, $this->hourTo, 30);
        $this->selectedTime = date('H') . ':' . sprintf('%02d', 30 * round(date('i') / 30));
        $this->days = $this->generateDays();
    }

    protected function initSlots()
    {
        $items = $this->loadSlots();

        foreach ($items as $item) {
            $timestampFrom = strtotime($item['datetime_from']);
            $timestampTo = strtotime($item['datetime_to']);
            $day = date('Y-m-d', $timestampFrom);
            $slotDate = date('Y-m-d H:i', $timestampFrom);
            $slotDateTo = date('Y-m-d H:i', $timestampTo);
            $timeFrom = date('H:i', $timestampFrom);
            $timeTo = date('H:i', $timestampTo);
            $hour = explode(':', $timeFrom)[0];

            $this->slots[$day][$slotDate] = [
                'status' => $item['status'],
                'text' => $item['text'],
                'timeFrom' => $timeFrom,
                'timeTo' => $timeTo,
                'from' => $slotDate,
                'to' => $slotDateTo,
                'offset' => ($hour - $this->hourFrom) * 60,
                'duration' => $this->countDuration($slotDate, $slotDateTo),
            ];
        }
    }

    protected function loadSlots()
    {
        // demo
        return [
            [
                'datetime_from' => date('Y-m-d 12:00:00', strtotime('today')), // datetime string
                'datetime_to' => date('Y-m-d 13:30:00', strtotime('today')), // datetime string
                'status' => 'available', // available, reserved, partially-avilable
                'text' => 'test free', // display text
            ],
            [
                'datetime_from' => date('Y-m-d 10:00:00', strtotime('today')), // datetime string
                'datetime_to' => date('Y-m-d 11:15:00', strtotime('today')), // datetime string
                'status' => 'reserved', // available, reserved, partially-avilable
                'text' => 'test reserved', // display text
            ],
        ];
    }

    protected function countDuration($from, $to)
    {
        return round((strtotime($to) - strtotime($from)) / 60);
    }
}
