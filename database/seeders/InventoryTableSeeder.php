<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "inventory";

    public function run()
    {
        DB::table('inventory')->insert([
            '料號' => '4017-01HL000',
            '現有庫存' => 60,
            '儲位' => '7-A019',
            '客戶別' => 'Fendi',
            '最後更新時間' => Carbon::create(2021, 6, 16, 11, 5, 53, 'GMT')
        ]);
        //
        DB::table('inventory')->insert([
            '料號' => '48M0-0102000',
            '現有庫存' => 100,
            '儲位' => '1-2',
            '客戶別' => 'ARRIS',
            '最後更新時間' => Carbon::create(2021, 3, 30, 13, 24, 29, 'GMT')
        ]);
        //
        DB::table('inventory')->insert([
            '料號' => '4152-01EV000',
            '現有庫存' => 18,
            '儲位' => '7-A05',
            '客戶別' => 'Omega',
            '最後更新時間' => Carbon::create(2020, 12, 24, 16, 19, 28, 'GMT')
        ]);
    }
}
