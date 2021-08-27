<?php

namespace Database\Seeders;
use App\Models\Outbound;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OutboundTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "outbound";

    public function run()
    {
        //
        $outbound = new Outbound;
        $outbound->客戶別 = 'Fendi';
        $outbound->機種 = 'EST';
        $outbound->製程 = 'FAE';
        $outbound->領用原因 = '生產領用';
        $outbound->線別 = 'EST 09';
        $outbound->料號 = '4152-01ER000';
        $outbound->品名 = '電動起子頭';
        $outbound->規格 = 'D4*40*D2*20*2IP,尾缺,固德';
        $outbound->單位 = 'pcs';
        $outbound->預領數量 = '800';
        $outbound->實際領用數量 = '360';
        $outbound->備註 = '';
        $outbound->實領差異原因 = '';
        $outbound->儲位 = '7-A008';
        $outbound->領料人員 = 'F';
        $outbound->領料人員工號 = NULL;
        $outbound->發料人員 = 'O';
        $outbound->發料人員工號 = NULL;
        $outbound->領料單號 = 202101080001;
        $outbound->開單時間 = Carbon::create(2021, 1, 18, 10, 43, 21, 'GMT');
        $outbound->出庫時間 = Carbon::create(2021, 1, 18, 17, 33, 56, 'GMT');
        $outbound->save();
        //
        $outbound = new Outbound;
        $outbound->客戶別 = 'NAPA';
        $outbound->機種 = 'ONYX';
        $outbound->製程 = 'FATP';
        $outbound->領用原因 = '生產領用';
        $outbound->線別 = 'A1';
        $outbound->料號 = '4606-03R6000';
        $outbound->品名 = '條碼';
        $outbound->規格 = '38*10';
        $outbound->單位 = 'PC';
        $outbound->預領數量 = '10';
        $outbound->實際領用數量 = '0';
        $outbound->備註 = '';
        $outbound->實領差異原因 = '';
        $outbound->儲位 = '';
<<<<<<< HEAD
        $outbound->領料人員 = '';
        $outbound->領料人員工號 = NULL;
        $outbound->發料人員 = '';
=======
        $outbound->領料人員 = NULL;
        $outbound->領料人員工號 = NULL;
        $outbound->發料人員 = NULL;
>>>>>>> 0827tony
        $outbound->發料人員工號 = NULL;
        $outbound->領料單號 = 202101150004;
        $outbound->開單時間 = Carbon::create(2021, 1, 15, 13, 26, 57, 'GMT');
        $outbound->出庫時間 = Carbon::create(1900, 1, 1, 0, 0, 0, 'GMT');
        $outbound->save();
        //
        $outbound = new Outbound;
        $outbound->客戶別 = 'Fendi';
        $outbound->機種 = 'SMR';
        $outbound->製程 = 'FATP';
        $outbound->領用原因 = 'NPI';
        $outbound->線別 = 'EST 09';
        $outbound->料號 = '48M0-0191000';
        $outbound->品名 = '超細纖維無塵布6001';
        $outbound->規格 = '9*9英吋100級熱線封邊,115克/平';
        $outbound->單位 = '包';
        $outbound->預領數量 = '60';
        $outbound->實際領用數量 = '10';
        $outbound->備註 = 'TEST';
        $outbound->實領差異原因 = '';
        $outbound->儲位 = '7-A010';
        $outbound->領料人員 = '羅軍文';
        $outbound->領料人員工號 = NULL;
        $outbound->發料人員 = '常雙良';
        $outbound->發料人員工號 = NULL;
        $outbound->領料單號 = 202101120001;
        $outbound->開單時間 = Carbon::create(2021, 1, 12, 9, 51, 46, 'GMT');
        $outbound->出庫時間 = Carbon::create(2021, 1, 18, 13, 53, 57, 'GMT');
        $outbound->save();
    }
}
