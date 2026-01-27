<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\Equipo;
use Illuminate\Support\Facades\Event;

use App\Observers\EquipoObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
        Equipo::observe(EquipoObserver::class);
        \App\Models\Monitor::observe(\App\Observers\MonitorObserver::class);
        \App\Models\DiscoDuro::observe(\App\Observers\DiscoDuroObserver::class);
        \App\Models\Periferico::observe(\App\Observers\PerifericoObserver::class);  
        \App\Models\Ram::observe(\App\Observers\RamObserver::class);     
        \App\Models\Procesador::observe(\App\Observers\ProcesadorObserver::class); 
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }


    //Observers
    protected $observers = [
        \App\Models\Equipo::class => \App\Observers\EquipoObserver::class,
    ];

}
