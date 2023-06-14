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
        \DB::table('月請購_單耗')->insert([
            '料號' => '4003-07R0000',
            '客戶別' => 'Fendi',
            '機種' => 'Vulcan',
            '製程' => 'FAE',
            '單耗' => 5,
            '狀態' => '已完成',
            '畫押信箱' => 'test@pegatroncorp.com',
            '送單時間' => Carbon::now()->format('Y-m-d H:i:s.v'),
            '送單人' => "1"
        ]);
        //
        \DB::table('月請購_單耗')->insert([
            '料號' => '4608-020K000',
            '客戶別' => 'Cisco',
            '機種' => 'EZ1K',
            '製程' => 'FATP',
            '單耗' => 0.0015,
            '狀態' => '已完成',
            '畫押信箱' => 'test2@pegatroncorp.com',
            '送單時間' => Carbon::now()->format('Y-m-d H:i:s.v'),
            '送單人' => "1"
        ]);
        //
        \DB::table('月請購_單耗')->insert([
            '料號' => '4152-01ER000',
            '客戶別' => 'Fendi',
            '機種' => 'Atlas',
            '製程' => 'LCON',
            '單耗' => 0.003,
            '狀態' => '已完成',
            '畫押信箱' => 'test2@pegatroncorp.com',
            '送單時間' => Carbon::now()->format('Y-m-d H:i:s.v'),
            '送單人' => "1"
        ]);
    }
}
