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
        //     $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        //     $out->writeln("Hello from Terminal");
        // })->everyMinute();
        $schedule->command('call:safestock')->daily()->timezone('Asia/Taipei');
        $schedule->command('call:sluggish')->daily()->timezone('Asia/Taipei');
        $schedule->command('barcodeimg:clear')->daily()->timezone('Asia/Taipei');
        $schedule->command('logs:clear')->quarterly('03:00')->timezone('Asia/Taipei'); // At 03:00 in every 3 month.
        $schedule->command('log:clear')->quarterly('03:00')->timezone('Asia/Taipei'); // At 03:00 in every 3 month.
        $schedule->command('telescope:prune --hours=48')->daily(); // Prune the laravel telescope records created over 48 hours ago

        // Clean up dated users
        $schedule->command('olduser:clear')->quarterly('02:00')->timezone('Asia/Taipei');

        // Clean up dated inventory
        $schedule->command('inventory:cleanup')->quarterly('01:00')->timezone('Asia/Taipei');
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