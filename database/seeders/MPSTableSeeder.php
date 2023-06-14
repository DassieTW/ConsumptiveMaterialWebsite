<?php

namespace Database\Seeders;

use App\Models\MPS;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MPSTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "MPS";

    public function run()
    {
        \DB::table('MPS')->insert([
            '客戶別' => 'Fendi',
            '機種' => 'Atlas',
            '製程' => 'FATP',
            '下月MPS' => 350000,
            '下月生產天數' => 27,
            '本月MPS' => 200000,
            '本月生產天數' => 12,
            '填寫時間' => Carbon::create(2021, 3, 23, 14, 9, 3, 'GMT')
        ]);
        //
        \DB::table('MPS')->insert([
            '客戶別' => 'Fendi',
            '機種' => 'NMN',
            '製程' => '無塵室',
            '下月MPS' => 45,
            '下月生產天數' => 54,
            '本月MPS' => 54,
            '本月生產天數' => 6,
            '填寫時間' => Carbon::create(2021, 1, 27, 17, 6, 52, 'GMT')
        ]);
        //
        \DB::table('MPS')->insert([
            '客戶別' => 'Cisco',
            '機種' => 'EZ1K',
            '製程' => 'FATP',
            '下月MPS' => 15000,
            '下月生產天數' => 24,
            '本月MPS' => 20000,
            '本月生產天數' => 26,
            '填寫時間' => Carbon::create(2021, 3, 25, 9, 42, 1, 'GMT')
        ]);
    }
}
