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
        // $schedule->call(function () { // test schedule in log file
        //     $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        //     $out->writeln("Hello from Terminal");
        //     info("Test");
        // })->everyMinute()->appendOutputTo(storage_path('logs/laravel.log'))->emailOutputTo('Vincent6_Yeh@pegatroncorp.com');
        $schedule->command('exchange:rate')->dailyAt("5:00")->timezone('Asia/Taipei')->appendOutputTo(storage_path('logs/laravel.log'))->emailOutputTo('Vincent6_Yeh@pegatroncorp.com')->runInBackground();
        $schedule->command('call:safestock')->dailyAt("6:00")->timezone('Asia/Taipei')->appendOutputTo(storage_path('logs/laravel.log'))->emailOutputTo('Vincent6_Yeh@pegatroncorp.com')->runInBackground();
        $schedule->command('call:sluggish')->dailyAt("6:00")->timezone('Asia/Taipei')->appendOutputTo(storage_path('logs/laravel.log'))->emailOutputTo('Vincent6_Yeh@pegatroncorp.com')->runInBackground();
        $schedule->command('barcodeimg:clear')->daily()->timezone('Asia/Taipei')->appendOutputTo(storage_path('logs/laravel.log'))->emailOutputTo('Vincent6_Yeh@pegatroncorp.com')->runInBackground();
        $schedule->command('logs:clear')->quarterly()->timezone('Asia/Taipei')->appendOutputTo(storage_path('logs/laravel.log'))->emailOutputTo('Vincent6_Yeh@pegatroncorp.com')->runInBackground(); // At 03:00 in every 3 month.
        $schedule->command('log:clear')->quarterly()->timezone('Asia/Taipei')->appendOutputTo(storage_path('logs/laravel.log'))->emailOutputTo('Vincent6_Yeh@pegatroncorp.com')->runInBackground(); // At 03:00 in every 3 month.
        $schedule->command('telescope:prune')->weeklyOn(3, '8:00')->timezone('Asia/Taipei')->appendOutputTo(storage_path('logs/laravel.log'))->emailOutputTo('Vincent6_Yeh@pegatroncorp.com')->runInBackground(); // Prune the laravel telescope records weekly

        // Clean up dated users
        $schedule->command('olduser:clear')->quarterly()->timezone('Asia/Taipei')->appendOutputTo(storage_path('logs/laravel.log'))->emailOutputTo('Vincent6_Yeh@pegatroncorp.com')->runInBackground();

        // Clean up dated inventory
        $schedule->command('inventory:cleanup')->quarterly()->timezone('Asia/Taipei')->appendOutputTo(storage_path('logs/laravel.log'))->emailOutputTo('Vincent6_Yeh@pegatroncorp.com')->runInBackground();
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