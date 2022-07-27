<?php

namespace Database\Seeders;
use App\Models\月請購_單耗;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class 月請購_單耗TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "月請購_單耗";

    public function run()
    {
        //
        $month = new 月請購_單耗;
        $month->料號 = '4003-07R0000';
        $month->客戶別 = 'Fendi';
        $month->機種 = 'Vulcan';
        $month->製程 = 'FAE';
        $month->單耗 = 5;
        $month->狀態 = '已完成';
        // $month->畫押工號 = 'LA2100001';
        $month->畫押信箱 = 'test@pegatroncorp.com';
        $month->送單時間 = Carbon::now()->format('Y-m-d H:i:s.v');
        $month->送單人 = "1";
        $month->save();
        //
        $month = new 月請購_單耗;
        $month->料號 = '4608-020K000';
        $month->客戶別 = 'Cisco';
        $month->機種 = 'EZ1K';
        $month->製程 = 'FATP';
        $month->單耗 = 0.0015;
        $month->狀態 = '已完成';
        // $month->畫押工號 = 'LA2100002';
        $month->畫押信箱 = 'test2@pegatroncorp.com';
        $month->送單時間 = Carbon::now()->format('Y-m-d H:i:s.v');
        $month->送單人 = "1";
        $month->save();
        //
        $month = new 月請購_單耗;
        $month->料號 = '4152-01ER000';
        $month->客戶別 = 'Fendi';
        $month->機種 = 'Atlas';
        $month->製程 = 'LCON';
        $month->單耗 = 0.003;
        $month->狀態 = '已完成';
        // $month->畫押工號 = 'LA2100002';
        $month->畫押信箱 = 'test2@pegatroncorp.com';
        $month->送單時間 = Carbon::now()->format('Y-m-d H:i:s.v');
        $month->送單人 = "1";
        $month->save();
    }
}
