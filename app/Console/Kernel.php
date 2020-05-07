<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\ScheduleList;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\GetEveMails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('get:evemails')
            ->everyTenMinutes()->sendOutputTo(storage_path() . "/logs/mail.recent");
        $schedule->command('get:evenotif')
            ->everyTenMinutes()->sendOutputTo(storage_path() . "/logs/mail.recent");
        $schedule->command('queue:work --tries=5 --stop-when-empty')
            ->everyMinute()->sendOutputTo(storage_path() . "/logs/queue.recent");
        $schedule->command('queue:flush')
            ->daily()->sendOutputTo(storage_path() . "/logs/queue.recent");
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
