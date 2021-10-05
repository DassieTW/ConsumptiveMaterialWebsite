<?php

namespace Database\Seeders;
use App\Models\O庫出庫退料;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class O庫出庫退料TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "O庫出庫退料";

    public function run()
    {
        //
        $out = new O庫出庫退料;
        $out->客戶別 = 'Cisco';
        $out->機種 = 'ARRIS';
        $out->製程 = 'FAE';
        $out->退回原因 = '不良更換';
        $out->線別 = '2N';
        $out->料號 = '0432-00KX000';
        $out->品名 = 'ADAPTER SWITCHING 15V/1.5A EU';
        $out->規格 = 'OEM/ADS0271-B 150150';
        $out->預退數量 = 699;
        $out->實際退回數量 = 87;
        $out->備註 = 'T1';
        $out->實退差異原因 = 'T';
        $out->庫別 = 'O庫測試1';
        $out->收料人員 = 'LA1501413';
        $out->收料人員工號 = 'Z';
        $out->退料人員 = 'LA1501413';
        $out->退料人員工號 = 'Z';
        $out->退料單號 = 202106270001;
        $out->開單時間 = Carbon::create(2021, 6, 27, 17, 6, 58, 'GMT');
        $out->入庫時間 = Carbon::create(2021, 6, 27, 17, 13, 8, 'GMT');
        $out->功能狀況 = '不良品';
        $out->save();
        //
        $out = new O庫出庫退料;
        $out->客戶別 = 'Cisco';
        $out->機種 = 'ARRIS';
        $out->製程 = 'FAE';
        $out->退回原因 = '不良更換';
        $out->線別 = '2N';
        $out->料號 = '0432-00KX000';
        $out->品名 = 'ADAPTER SWITCHING 15V/1.5A EU';
        $out->規格 = 'OEM/ADS0271-B 150150';
        $out->預退數量 = 699;
        $out->實際退回數量 = 90;
        $out->備註 = 'T1';
        $out->實退差異原因 = 't';
        $out->庫別 = 'O庫測試2';
        $out->收料人員 = 'LA1501413';
        $out->收料人員工號 = 'Z';
        $out->退料人員 = 'LA1501413';
        $out->退料人員工號 = 'Z';
        $out->退料單號 = 202106270001;
        $out->開單時間 = Carbon::create(2021, 6, 27, 17, 6, 58, 'GMT');
        $out->入庫時間 = Carbon::create(2021, 6, 27, 17, 13, 9, 'GMT');
        $out->功能狀況 = '良品';
        $out->save();

    }
}
