<?php

namespace Database\Seeders;
use App\Models\Inventory;
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
        //
        $inventory = new Inventory;
        $inventory->料號 = '4017-01HL000';
        $inventory->現有庫存 = 60;
        $inventory->儲位 = '7-A019';
        $inventory->客戶別 = 'Fendi';
        $inventory->最後更新時間 = Carbon::create(2021, 6, 16, 11, 5, 53, 'GMT');
        $inventory->save();
        //
        $inventory = new Inventory;
        $inventory->料號 = '48M0-0102000';
        $inventory->現有庫存 = 100;
        $inventory->儲位 = '1-2';
        $inventory->客戶別 = 'ARRIS';
        $inventory->最後更新時間 = Carbon::create(2021, 3, 30, 13, 24, 29, 'GMT');
        $inventory->save();
        //
        $inventory = new Inventory;
        $inventory->料號 = '4152-01EV000';
        $inventory->現有庫存 = 18;
        $inventory->儲位 = '7-A05';
        $inventory->客戶別 = 'Omega';
        $inventory->最後更新時間 = Carbon::create(2020, 12, 24, 16, 19, 28, 'GMT');
        $inventory->save();
    }
}
