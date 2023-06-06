<?php

namespace App\Console;

use App\Models\Record;
use App\Models\User;
use App\Services\SheduledDelete;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            $weekAgo = Carbon::now()->subWeek();
            SheduledDelete::deleteRecords($weekAgo);
        })
        ->dailyAt('06:00');

        $schedule->call(function() {
            $yearAgo = Carbon::now()->subYear();
            SheduledDelete::deleteUsers($yearAgo);
        })
        ->weekly()
        ->saturdays()
        ->at('06:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
