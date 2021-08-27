<?php

namespace Database\Seeders;
use App\Models\請購單;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class 請購單TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "請購單";

    public function run()
    {
        //
        $list = new 請購單;
        $list->SRM單號 = 'DDD';
        $list->客戶 = 'Fendi';
        $list->料號 = '4617-0059000';
        $list->品名 = '碳帶';
        $list->MOQ = 5;
        $list->下月需求 = 4.8475;
        $list->當月需求 = 2.77;
        $list->安全庫存 = 10.772222222;
        $list->單價 = 9.7;
        $list->幣別 = 'USD';
        $list->匯率 = 6;
        $list->在途數量 = 0;
        $list->現有庫存 =35;
        $list->本次請購數量 = 0;
        $list->實際需求 = -16.6102777777;
        $list->請購金額 = 0;
        $list->請購占比 = 0;
        $list->需求金額 = 282.1245;
        $list->需求占比 = 0.000513669001524847;
        $list->請購時間 = Carbon::create(2021, 6, 23, 14, 19, 45, 'GMT');
        $list->SXB單號 = 'DDDSA';
        $list->save();
        //

    }
}
