<?php

namespace Database\Seeders;
use App\Models\Inbound;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InboundTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "inbound";

    public function run()
    {
        //
        $inbound = new Inbound;
        $inbound->入庫單號 = 202012240001;
        $inbound->料號 = '4152-01EV000';
        $inbound->入庫數量 = 10;
        $inbound->儲位 = '7-A23';
        $inbound->入庫人員 = 'LA12121';
        $inbound->客戶別 = 'Fendi';
        $inbound->入庫原因 = '來料';
        $inbound->入庫時間 = Carbon::create(2020, 12, 24, 16, 14, 57, 'GMT');
        $inbound->save();
        //
        $inbound = new Inbound;
        $inbound->入庫單號 = 202103130007;
        $inbound->料號 = '48M0-000VB000';
        $inbound->入庫數量 = 2000;
        $inbound->儲位 = '7-A008';
        $inbound->入庫人員 = '工號';
        $inbound->客戶別 = 'Fendi';
        $inbound->入庫原因 = '來料';
        $inbound->入庫時間 = Carbon::create(2021, 3, 13, 15, 57, 8, 'GMT');
        $inbound->save();
        //
        $inbound = new Inbound;
        $inbound->入庫單號 = 202103300001;
        $inbound->料號 = '48M0-0102000';
        $inbound->入庫數量 = 100;
        $inbound->儲位 = '1-2';
        $inbound->入庫人員 = 'S09193426';
        $inbound->客戶別 = 'ARRIS';
        $inbound->入庫原因 = '調撥';
        $inbound->入庫時間 = Carbon::create(2021, 3, 30, 13, 24, 29, 'GMT');
        $inbound->save();
    }
}
