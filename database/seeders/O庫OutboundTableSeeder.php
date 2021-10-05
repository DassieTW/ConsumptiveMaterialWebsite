<?php

namespace Database\Seeders;
use App\Models\O庫Outbound;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class O庫OutboundTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "O庫outbound";

    public function run()
    {
        //
        $outbound = new O庫Outbound;
        $outbound->客戶別 = 'Cisco';
        $outbound->機種 = 'ALL';
        $outbound->製程 = 'FAE';
        $outbound->領用原因 = '不良更換';
        $outbound->線別 = '2C';
        $outbound->料號 = '0432-00KX000';
        $outbound->品名 = 'ADAPTER SWITCHING 15V/1.5A EU';
        $outbound->規格 = 'OEM/ADS0271-B 150150';
        $outbound->預領數量 = '60';
        $outbound->實際領用數量 = '1';
        $outbound->備註 = '';
        $outbound->實領差異原因 = 't';
        $outbound->庫別 = 'O庫測試';
        $outbound->領料人員 = 'Z';
        $outbound->領料人員工號 = 'LA1501413';
        $outbound->發料人員 = 'Z';
        $outbound->發料人員工號 = 'LA1501413';
        $outbound->領料單號 = 202106270002;
        $outbound->開單時間 = Carbon::create(2021, 6, 27, 11, 8, 55, 'GMT');
        $outbound->出庫時間 = Carbon::create(2021, 6, 27, 16, 24, 46, 'GMT');
        $outbound->save();
        //

    }
}
