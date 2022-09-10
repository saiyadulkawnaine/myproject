<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\TargetCron::class,
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
        /*$schedule->command('target:cron')
                  ->everyMinute();*/
        $schedule->command('mktcostsecondapproved:cron')
                  ->twiceDaily(6, 18);
        $schedule->command('shipmentpending:cron')
                 ->daily(6);
        $schedule->command('mrireport:cron')
                  ->daily();
        $schedule->command('dailyprofitloss:cron')
                  ->daily(7);
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
