<?php 

namespace App\Console;
use Cron\CronExpression;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;
use App\Models\Gatherings;

class ScheduleList
{
    public function getCommands()
    {
        $schedule = app(Schedule::class);

        $this->registerCommands($schedule);
        $scheduledCommands = collect($schedule->events())
            ->map(function ($event) {
                $expression = CronExpression::factory($event->expression);

                return [
                    'callback' => $event->description,
//                    'command' => $event->command,
                    'expression' => $event->expression,
                    'next-execution' => $expression->getNextRunDate()
                ];
            });

        return $scheduledCommands;
    }
    public function registerCommands(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $gathers = \App\Models\Gatherings::all();
       foreach($gathers as $gather) {
            $frequency = $gather->frequency;
//            $schedule->call('App\Http\Controllers\GatherController@'.$gather->route)->description($gather->route)->cron($frequency); 
        }
    }
    
}