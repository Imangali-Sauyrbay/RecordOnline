<?php

namespace App\Listeners;

use App\Events\ReloadRoutes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use TCG\Voyager\Events\BreadAdded;

class ReloadRoutesListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BreadAdded  $event
     * @return void
     */
    public function handle(BreadAdded $event)
    {
        shell_exec('php /var/www/html/artisan octane:reload');
    }
}
