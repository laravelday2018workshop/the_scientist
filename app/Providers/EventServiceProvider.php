<?php

declare(strict_types=1);

namespace App\Providers;

use Acme\Academic\Event\AcademicWasCreatedEvent;
use App\Listeners\SendConfirmationEmailToAcademic;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AcademicWasCreatedEvent::class => [
            SendConfirmationEmailToAcademic::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    }
}
