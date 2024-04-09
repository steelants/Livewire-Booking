<?php

namespace SteelAnts\Booking;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use SteelAnts\Booking\Livewire\Calendar;

class BookingServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views/livewire', 'booking');
        Livewire::component('booking-calendar', Calendar::class);

        $this->publishes([
            __DIR__.'/../stubs/resources/js' => resource_path('js/vendor/booking'),
            __DIR__.'/../stubs/resources/sass' => resource_path('sass/vendor/booking'),
        ], 'booking-assets');
    }

    public function register()
    {
    }
}
