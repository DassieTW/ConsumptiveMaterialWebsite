<?php

namespace Database\Seeders;
use App\Models\出庫退料;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class 出庫退料TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "出庫退料";

    public function run()
    {
        //
        $out = new 出庫退料;
        $out->客戶別 = 'Fendi';
        $out->機種 = 'Vulcan';
        $out->製程 = 'FAE';
        $out->退回原因 = '不良更換';
        $out->線別 = '2C';
        $out->料號 = '4152-01ER000';
        $out->品名 = '電動起子頭';
        $out->規格 = 'D4*40*D2*20*2IP,尾缺,固德';
        $out->單位 = 'pcs';
        $out->預退數量 = 10;
        $out->實際退回數量 = 414;
        $out->備註 = '';
        $out->實退差異原因 = 't';
        $out->儲位 = '1-3';
        $out->收料人員 = 'LA1501413';
        $out->收料人員工號 = 'Z';
        $out->退料人員 = 'LA1501413';
        $out->退料人員工號 = 'Z';
        $out->退料單號 = 202106170001;
        $out->開單時間 = Carbon::create(2021, 6, 17, 10, 49, 58, 'GMT');
        $out->入庫時間 = Carbon::create(2021, 6, 18, 14, 56, 26, 'GMT');
        $out->功能狀況 = '不良品';
        $out->save();
        //
        $out = new 出庫退料;
        $out->客戶別 = 'Fendi';
        $out->機種 = 'Vulcan';
        $out->製程 = 'FAE';
        $out->退回原因 = '不良更換';
        $out->線別 = '2C';
        $out->料號 = '4152-01ER000';
        $out->品名 = '電動起子頭';
        $out->規格 = 'D4*40*D2*20*2IP,尾缺,固德';
        $out->單位 = 'pcs';
        $out->預退數量 = 10;
        $out->實際退回數量 = 499;
        $out->備註 = '';
        $out->實退差異原因 = 'tt';
        $out->儲位 = '1-2';
        $out->收料人員 = 'LA1501413';
        $out->收料人員工號 = 'Z';
        $out->退料人員 = 'LA1501413';
        $out->退料人員工號 = 'Z';
        $out->退料單號 = 202106170001;
        $out->開單時間 = Carbon::create(2021, 6, 17, 10, 49, 58, 'GMT');
        $out->入庫時間 = Carbon::create(2021, 6, 18, 14, 56, 27, 'GMT');
        $out->功能狀況 = '良品';
        $out->save();

    }
}
