<?php

namespace Database\Seeders;
use App\Models\月請購_站位;
use Illuminate\Database\Seeder;

class 月請購_站位TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "月請購_站位";

    public function run()
    {
        //
        $month = new 月請購_站位;
        $month->料號 = '4107-007T000';
        $month->客戶別 = 'Fendi';
        $month->機種 = 'Atlas';
        $month->製程 = 'FAE';
        $month->當月站位人數 = 20;
        $month->當月開線數 = 1;
        $month->當月開班數 = 1;
        $month->當月每人每日需求量 = 2;
        $month->當月每日更換頻率 = 1;
        $month->下月站位人數 = 20;
        $month->下月開線數 = 2;
        $month->下月開班數 = 1;
        $month->下月每人每日需求量 = 2;
        $month->下月每日更換頻率 = 1;
        $month->狀態 = '已完成';
        $month->畫押工號 = 'LA2100001';
        $month->畫押信箱 = 'test@pegatroncorp.com';
        $month->save();
        //
        $month = new 月請購_站位;
        $month->料號 = '48M0-00QK000';
        $month->客戶別 = 'Omega';
        $month->機種 = 'VS1';
        $month->製程 = '前側';
        $month->當月站位人數 = 7;
        $month->當月開線數 = 6;
        $month->當月開班數 = 6;
        $month->當月每人每日需求量 = 6;
        $month->當月每日更換頻率 = 6;
        $month->下月站位人數 = 6;
        $month->下月開線數 = 6;
        $month->下月開班數 = 5;
        $month->下月每人每日需求量 = 5;
        $month->下月每日更換頻率 = 5;
        $month->狀態 = '已完成';
        $month->畫押工號 = 'LA2100001';
        $month->畫押信箱 = 'test@pegatroncorp.com';
        $month->save();
        //
        $month = new 月請購_站位;
        $month->料號 = '48M0-002V000';
        $month->客戶別 = 'Fendi';
        $month->機種 = 'Atlas';
        $month->製程 = 'FATP';
        $month->當月站位人數 = 27;
        $month->當月開線數 = 2;
        $month->當月開班數 = 1;
        $month->當月每人每日需求量 = 1;
        $month->當月每日更換頻率 = 0.1428571;
        $month->下月站位人數 = 27;
        $month->下月開線數 = 2;
        $month->下月開班數 = 1;
        $month->下月每人每日需求量 = 1;
        $month->下月每日更換頻率 = 0.1428571;
        $month->狀態 = '已完成';
        $month->畫押工號 = 'LA2100001';
        $month->畫押信箱 = 'test@pegatroncorp.com';
        $month->save();
    }
}
