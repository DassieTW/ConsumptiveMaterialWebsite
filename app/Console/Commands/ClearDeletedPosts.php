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
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class ClearDeletedPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除已軟刪除文章';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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

        // \Log::info('移除所有被刪除文章');

        $datas = DB::table('月請購_單耗')
            ->join('MPS', function ($join) {
                $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                    ->on('MPS.機種', '=', '月請購_單耗.機種')
                    ->on('MPS.製程', '=', '月請購_單耗.製程');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
            })
            ->where('月請購_單耗.狀態', '=', "已完成")
            ->get();
        foreach ($datas as $data) {

            if ($data->月請購 === '否') {
                $safe = $data->安全庫存;
            } else {
                $safe =  $data->LT * $data->單耗 * $data->下月MPS / $data->下月生產天數;
            }

            $data->安全庫存 = $safe;
        }

        $datas1 = DB::table('月請購_站位')
            ->join('MPS', function ($join) {
                $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                    ->on('MPS.機種', '=', '月請購_站位.機種')
                    ->on('MPS.製程', '=', '月請購_站位.製程');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
            })->get();

        foreach ($datas1 as $data) {

            if ($data->月請購 === '否') {
                $safe = $data->安全庫存;
            } else {
                $safe =  $data->LT * $data->下月站位人數 * $data->下月開線數 * $data->下月開班數 * $data->下月每人每日需求量 * $data->下月每日更換頻率 / $data->MPQ;
            }

            $data->安全庫存 = $safe;
        }

        $data = array('data' => $datas, 'data1' => $datas1);

        \Mail::send('call/safe', $data, function ($message) {
            // $email = 'Tony_Tseng@pegatroncorp.com';
            $email = 't22923200@gmail.com';
            $message->to($email, 'Tutorials Point')->subject('Check Consume data');
            $message->from('ConsumablesManagement@pegatroncorp.com', 'Consumables Management');
        });
    } // handle
}
