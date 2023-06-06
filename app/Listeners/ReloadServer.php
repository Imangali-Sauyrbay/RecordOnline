<?php

namespace App\Listeners;



class ReloadServer
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        if (array_key_exists('LARAVEL_OCTANE', $_SERVER) && (int) $_SERVER['LARAVEL_OCTANE']) {
            shell_exec('php /var/www/html/artisan octane:reload');
        }
    }
}
