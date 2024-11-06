<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Services\MailService;


class SluggishStock extends Command
{
    private $mailservice;
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
    protected $description = '呆滯天數定期寄信';

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
        $this->warn("Now Running [call:sluggish]");
        try {
            \Log::channel('dbquerys')->info('---------------------------開始寄信 by Sluggish Stock Command--------------------------');
            $this->mailservice->day();
            \Log::channel('dbquerys')->info('---------------------------寄信結束 by Sluggish Stock Command--------------------------');
            $this->info("[call:sluggish] Command executed successfully!");
        } catch (Exception $e) {
            $this->error("[call:sluggish] Command execution failed with error : " . $e->getMessage());
            \Log::error("[call:sluggish] Command execution failed with error : " . $e->getMessage());
        } // try - catch

        return 0;
    }
}