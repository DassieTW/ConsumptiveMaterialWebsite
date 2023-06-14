<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Services\MailService;

class SendSafeStockMail extends Command
{
    private $mailservice;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:safestock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '安全庫存';

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
        // $posts = Models\Post::onlyTrashed()->get();
        // var_dump($posts); // test
        // foreach ($posts as $post) {
        //     $post->forceDelete();
        // } // for each

        try {
            \Log::channel('dbquerys')->info('---------------------------開始寄信 by Safe Stock Command--------------------------');
            $this->mailservice->download();
            \Log::channel('dbquerys')->info('---------------------------寄信結束 by Safe Stock Command--------------------------');
            $this->info("Command executed successfully!");
        } catch (Exception $e) {
            $this->error("Command execution failed with error : " . $e->getMessage());
            \Log::error("Command execution failed with error : " . $e->getMessage());
        } // try - catch

        return 0;
    } // handle
}