<?php

namespace Database\Seeders;

use App\Models\O庫不良品Inventory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class O庫不良品InventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "O庫不良品inventory";

    public function run()
    {
        \DB::table('O庫不良品inventory')->insert([
            '料號' => '0432-00KX000',
            '現有庫存' => 87,
            '客戶別' => 'Cisco',
            '庫別' => 'O庫測試1',
            '最後更新時間' => Carbon::create(2021, 6, 27, 17, 13, 8, 'GMT'),
            '品名' => 'ADAPTER SWITCHING 15V/1.5A EU',
            '規格' => 'OEM/ADS0271-B 150150'
        ]);
        //
        \DB::table('O庫不良品inventory')->insert([
            '料號' => '0432-00KX000',
            '現有庫存' => 88,
            '客戶別' => 'Fendi',
            '庫別' => 'O庫測試2',
            '最後更新時間' => Carbon::create(2021, 6, 27, 17, 13, 8, 'GMT'),
            '品名' => 'ADAPTER SWITCHING 15V/1.5A EU',
            '規格' => 'OEM/ADS0271-B 150150'
        ]);
    }
}
