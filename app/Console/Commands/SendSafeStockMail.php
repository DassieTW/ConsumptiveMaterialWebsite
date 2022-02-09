<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models;
use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\客戶別;
use App\Models\機種;
use App\Models\製程;
use App\Models\線別;
use App\Models\領用原因;
use App\Models\退回原因;
use App\Models\入庫原因;
use App\Models\Outbound;
use App\Models\出庫退料;
use App\Models\人員信息;
use App\Models\儲位;
use App\Models\在途量;
use App\Models\Inventory;
use App\Models\不良品Inventory;
use App\Models\Inbound;
use App\Models\ConsumptiveMaterial;
use App\Models\請購單;
use App\Models\月請購_單耗;
use App\Models\月請購_站位;
use App\Models\非月請購;
use App\Models\MPS;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Services\MailService;

class SendSafeStockMail extends Command
{
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
    public function __construct( MailService $mailservice )
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

        \Log::channel('dbquerys')->info('---------------------------開始寄信 by Safe Stock Command--------------------------');
        $this->mailservice->download();
        \Log::channel('dbquerys')->info('---------------------------寄信結束 by Safe Stock Command--------------------------');
        return 0;
    } // handle
}
