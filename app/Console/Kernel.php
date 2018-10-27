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
        Commands\Init::class,
        Commands\Scan::class,
        Commands\Watch::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('mangapie:scan')
//                 ->everyFiveMinutes();

        if (\Cache::tags(['config', 'heat'])->get('enabled') === true)
            $schedule->job(new \App\Jobs\DecreaseHeats())->hourly();

        if (\Config::get('app.image.clean') === true)
            $schedule->job(new \App\Jobs\CleanupImageDisk())->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
