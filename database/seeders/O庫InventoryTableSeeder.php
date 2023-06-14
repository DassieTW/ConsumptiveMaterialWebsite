<?php

namespace Database\Seeders;

use App\Models\O庫Inventory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class O庫InventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "O庫inventory";

    public function run()
    {
        \DB::table('O庫inventory')->insert([
            '料號' => '0432-00KX000',
            '現有庫存' => 51,
            '客戶別' => 'Cisco',
            '庫別' => 'O庫測試',
            '最後更新時間' => Carbon::create(2021, 6, 27, 16, 24, 47, 'GMT'),
            '品名' => 'ADAPTER SWITCHING 15V/1.5A EU',
            '規格' => 'OEM/ADS0271-B 150150'
        ]);
        //
        \DB::table('O庫inventory')->insert([
            '料號' => '0432-00KX000',
            '現有庫存' => 89,
            '客戶別' => 'Fendi',
            '庫別' => 'O庫測試1',
            '最後更新時間' => Carbon::create(2021, 6, 27, 17, 13, 8, 'GMT'),
            '品名' => 'ADAPTER SWITCHING 15V/1.5A EU',
            '規格' => 'OEM/ADS0271-B 150150'
        ]);
        //
        \DB::table('O庫inventory')->insert([
            '料號' => 'TEST3',
            '現有庫存' => 264,
            '客戶別' => 'Fendi',
            '庫別' => '9743',
            '最後更新時間' => Carbon::create(2021, 7, 2, 15, 29, 20, 'GMT'),
            '品名' => '品名3',
            '規格' => '規格3'
        ]);
    }
}
