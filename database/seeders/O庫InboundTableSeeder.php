<?php

namespace Database\Seeders;
use App\Models\O庫Inbound;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class O庫InboundTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "O庫inbound";

    public function run()
    {
        //
        $oinbound = new O庫Inbound;
        $oinbound->入庫單號 = 202106250001;
        $oinbound->料號 = '0432-00KX000';
        $oinbound->品名 = 'ADAPTER SWITCHING 15V/1.5A EU';
        $oinbound->規格 = 'OEM/ADS0271-B 150150';
        $oinbound->客戶別 = 'Cisco';
        $oinbound->庫別 = 'O庫測試';
        $oinbound->數量 = 60;
        $oinbound->入庫人員 = 'LA1501413';
        $oinbound->時間 = Carbon::create(2021, 6, 25, 16, 47, 25, 'GMT');
        $oinbound->入庫原因 = '來料';
        $oinbound->save();
        //
    }
}
