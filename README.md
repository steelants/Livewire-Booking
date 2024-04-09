# Livewire Booking Calendar

```
<?php

namespace App\Livewire;

use SteelAnts\Booking\Livewire\Calendar;

class CustomCalendar extends Calendar
{

    public function mount()
    {
        $this->renderType = 'freehand';
        $this->dateFrom = date('Y-m-d', strtotime('today'));
        $this->dateTo = date('Y-m-d', strtotime('+7 days'));
        $this->selectedDay = date('Y-m-d', strtotime('today'));

        // required action
        $this->init();
    }

    protected function loadSlots()
    {
        // demo
        return [
            [
                'datetime_from' => date('Y-m-d 12:00:00', strtotime('today')), // datetime string
                'datetime_to' => date('Y-m-d 15:30:00', strtotime('today')), // datetime string
                'status' => 'available', // available, reserved, partially-avilable
                'text' => 'test free', // display text
            ],
            [
                'datetime_from' => date('Y-m-d 09:00:00', strtotime('today')), // datetime string
                'datetime_to' => date('Y-m-d 11:15:00', strtotime('today')), // datetime string
                'status' => 'reserved', // available, reserved, partially-avilable
                'text' => 'test reserved', // display text
            ],
        ];
    }
}
```