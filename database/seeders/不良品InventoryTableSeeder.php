<?php

namespace Database\Seeders;

use App\Models\不良品Inventory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class 不良品InventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "不良品inventory";

    public function run()
    {
        \DB::table('不良品inventory')->insert([
            '料號' => '48M0-00QK000',
            '現有庫存' => 499,
            '客戶別' => 'Cisco',
            '儲位' => '1-2',
            '最後更新時間' => Carbon::create(2021, 6, 25, 14, 31, 31, 'GMT')
        ]);
    }
}
