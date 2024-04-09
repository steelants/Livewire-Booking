# Livewire Booking Calendar

### Created by: [SteelAnts s.r.o.](https://www.steelants.cz/)
[![Total Downloads](https://img.shields.io/packagist/dt/steelants/booking.svg?style=flat-square)](https://packagist.org/packages/steelants/laravel-boilerplate)


## Installation
```bash
composer require steelants/booking
```

### Import assets
```bash
php artisan vendor:publish --tag=booking-assets
```

```scss
// app.scss
@import "./vendor/booking/booking.scss";
```

```js
// app.js
import './vendor/booking/booking.js';
```

## Usage
```php
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

## Contributors
<a href="https://github.com/steelants/Livewire-Booking/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=steelants/Livewire-Booking" />
</a>

## Other Packages
[steelants/laravel-auth](https://github.com/steelants/laravel-auth)

[steelants/laravel-boilerplate](https://github.com/steelants/Laravel-Boilerplate)

[steelants/datatable](https://github.com/steelants/Livewire-DataTable)

[steelants/form](https://github.com/steelants/Laravel-Form)

[steelants/modal](https://github.com/steelants/Livewire-Modal)

[steelants/laravel-tenant](https://github.com/steelants/Laravel-Tenant)
