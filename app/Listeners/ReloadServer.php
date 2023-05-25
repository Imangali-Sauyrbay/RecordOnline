<?php

namespace App\Listeners;

use App\Events\ReloadRoutes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReloadServer
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
     * @return void
     */
    public function handle()
    {
        shell_exec('php /var/www/html/artisan octane:reload');
    }
}
