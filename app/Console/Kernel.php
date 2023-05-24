<?php

namespace App\Console;

use App\Models\Record;
use App\Models\User;
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
            Record::whereDate('timestamp', '<', $weekAgo)->delete();
        })->dailyAt('06:00');

        $schedule->call(function() {
            $yearAgo = Carbon::now()->subYear();

            $users = User::onlyTrashed()
                ->whereDate('deleted_at', '<', $yearAgo)
                ->get();

            foreach ($users as $user) {
                if(Storage::disk('public')->fileExists($user->avatar)
                && !mb_ereg_match('.*default.png$', $user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }

            User::onlyTrashed()
                ->whereDate('deleted_at', '<', $yearAgo)
                ->forceDelete();
        })->weekly()->saturdays()->at('06:00');
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
