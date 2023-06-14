<?php

namespace Database\Seeders;

use App\Models\月請購_站位;
use Carbon\Carbon;
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
        \DB::table('月請購_站位')->insert([
            '料號' => '48M0-00QK000',
            '客戶別' => 'Omega',
            '機種' => 'VS1',
            '製程' => '前側',
            '當月站位人數' => 7,
            '當月開線數' => 6,
            '當月開班數' => 6,
            '當月每人每日需求量' => 6,
            '當月每日更換頻率' => 6,
            '下月站位人數' => 6,
            '下月開線數' => 6,
            '下月開班數' => 5,
            '下月每人每日需求量' => 5,
            '下月每日更換頻率' => 5,
            '狀態' => '已完成',
            '畫押信箱' => 'test@pegatroncorp.com',
            '送單時間' => Carbon::now()->format('Y-m-d H:i:s.v'),
            '送單人' => "1",
        ]);
        //
        \DB::table('月請購_站位')->insert([
            '料號' => '4107-007T000',
            '客戶別' => 'Fendi',
            '機種' => 'Atlas',
            '製程' => 'FAE',
            '當月站位人數' => 20,
            '當月開線數' => 1,
            '當月開班數' => 1,
            '當月每人每日需求量' => 2,
            '當月每日更換頻率' => 1,
            '下月站位人數' => 20,
            '下月開線數' => 2,
            '下月開班數' => 1,
            '下月每人每日需求量' => 2,
            '下月每日更換頻率' => 1,
            '狀態' => '已完成',
            '畫押信箱' => 'test@pegatroncorp.com',
            '送單時間' => Carbon::now()->format('Y-m-d H:i:s.v'),
            '送單人' => "1"
        ]);
        //
        \DB::table('月請購_站位')->insert([
            '料號' => '48M0-002V000',
            '客戶別' => 'Fendi',
            '機種' => 'Atlas',
            '製程' => 'FATP',
            '當月站位人數' => 27,
            '當月開線數' => 2,
            '當月開班數' => 1,
            '當月每人每日需求量' => 1,
            '當月每日更換頻率' => 0.1428571,
            '下月站位人數' => 27,
            '下月開線數' => 2,
            '下月開班數' => 1,
            '下月每人每日需求量' => 1,
            '下月每日更換頻率' => 0.1428571,
            '狀態' => '已完成',
            '畫押信箱' => 'test@pegatroncorp.com',
            '送單時間' => Carbon::now()->format('Y-m-d H:i:s.v'),
            '送單人' => "1"
        ]);
    }
}
