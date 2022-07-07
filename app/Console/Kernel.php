<?php

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendSafeStockMail::class,
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
        $schedule->command('call:safestock')->daily()->timezone('Asia/Taipei');
        $schedule->command('call:sluggish')->daily()->timezone('Asia/Taipei');
        $schedule->command('barcodeimg:clear')->daily()->timezone('Asia/Taipei');
        $schedule->command('logs:clear')->cron('30 03 01 Jan,Apr,Jul,Oct *')->timezone('Asia/Taipei'); // At 03:00 in every 3rd month.
        $schedule->command('log:clear')->cron('30 03 01 Jan,Apr,Jul,Oct *')->timezone('Asia/Taipei'); // At 03:00 in every 3rd month.
    } // schedule

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
