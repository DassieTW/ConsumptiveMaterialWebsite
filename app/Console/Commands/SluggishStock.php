<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Services\MailService;


class SluggishStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:sluggish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '呆滯天數';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MailService $mailservice)
    {
        parent::__construct();
        $this->mailservice = $mailservice;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            \Log::channel('dbquerys')->info('---------------------------開始寄信 by Sluggish Stock Command--------------------------');
            $this->mailservice->day();
            \Log::channel('dbquerys')->info('---------------------------寄信結束 by Sluggish Stock Command--------------------------');
            $this->info("Command executed successfully!");
        } catch (Exception $e) {
            $this->error("Command execution failed with error : " . $e->getMessage());
        } // try - catch

        return 0;
    }
}
