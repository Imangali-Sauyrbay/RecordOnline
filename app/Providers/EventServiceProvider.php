<?php

namespace App\Providers;

use App\Events\ReloadServerEvent;
use App\Listeners\ReloadServer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use TCG\Voyager\Events\BreadAdded;
use TCG\Voyager\Events\SettingUpdated;

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

        BreadAdded::class => [
            ReloadServer::class,
        ],

        SettingUpdated::class => [
            ReloadServer::class,
        ],

        ReloadServerEvent::class => [
            ReloadServer::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
