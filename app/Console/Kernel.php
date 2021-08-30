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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // $schedule->call(function () { // test schedule in log file
        //     \Log::info('成功排程');
        // })->everyMinute();

        $schedule->command('logs:clear')->cron('0 3 * */3 *')->timezone('Asia/Taipei'); // At 03:00 in every 3rd month.
        $schedule->command('log:clear')->cron('0 3 * */3 *')->timezone('Asia/Taipei'); // At 03:00 in every 3rd month.
    } // schedule

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
