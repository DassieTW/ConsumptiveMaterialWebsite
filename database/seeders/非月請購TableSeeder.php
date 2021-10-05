<?php

namespace Database\Seeders;
use App\Models\非月請購;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class 非月請購TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "非月請購";

    public function run()
    {
        //
        $unmonth = new 非月請購;
        $unmonth->客戶別 = 'Fendi';
        $unmonth->料號 = '4152-01EV000';
        $unmonth->請購數量 = -49;
        $unmonth->上傳時間 = Carbon::create(2020, 12, 10, 15, 46, 30, 'GMT');
        $unmonth->說明 = '';
        $unmonth->SXB單號 = NULL;
        $unmonth->save();
        //
        $unmonth = new 非月請購;
        $unmonth->客戶別 = 'ARRIS';
        $unmonth->料號 = '48M0-013T000';
        $unmonth->請購數量 = 100;
        $unmonth->上傳時間 = Carbon::create(2021, 3, 30, 14, 37, 0, 'GMT');
        $unmonth->說明 = '五廠SMT庫存入庫';
        $unmonth->SXB單號 = NULL;
        $unmonth->save();
        //
        $unmonth = new 非月請購;
        $unmonth->客戶別 = 'UI';
        $unmonth->料號 = '4608-020F000';
        $unmonth->請購數量 = 1300;
        $unmonth->上傳時間 = Carbon::create(2021, 5, 27, 17, 3, 56, 'GMT');
        $unmonth->說明 = '';
        $unmonth->SXB單號 = 'SXB-01053681';
        $unmonth->save();

    }
}
