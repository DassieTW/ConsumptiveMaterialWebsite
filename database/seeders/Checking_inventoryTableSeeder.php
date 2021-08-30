<?php

namespace Database\Seeders;
use App\Models\Checking_inventory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Checking_inventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        '單號'
        '料號'
        '現有庫存'
        '儲位'
        '客戶別'
        '盤點'
        */

        $inventory = new Checking_inventory;
        $inventory->單號 = "1_2021-03-17_15:29:32";
        $inventory->料號 = '4150-00A8000';
        $inventory->現有庫存 = 60;
        $inventory->儲位 = '7-A019';
        $inventory->客戶別 = 'Fendi';
        $inventory->盤點 = 0;
        $inventory->save();
        //
        $inventory = new Checking_inventory;
        $inventory->單號 = "1_2021-03-17_15:29:32";
        $inventory->料號 = '4017-01HL000';
        $inventory->現有庫存 = 100;
        $inventory->儲位 = '1-2';
        $inventory->客戶別 = 'ARRIS';
        $inventory->盤點 = 50;
        $inventory->save();
        //
        $inventory = new Checking_inventory;
        $inventory->單號 = "1_2021-03-17_15:29:32";
        $inventory->料號 = '4152-01EV000';
        $inventory->現有庫存 = 18;
        $inventory->儲位 = '7-A05';
        $inventory->客戶別 = 'Omega';
        $inventory->盤點 = 18;
        $inventory->save();
        //-----------------------------------------------
        $inventory = new Checking_inventory;
        $inventory->單號 = "2_2021-04-20_10:21:15";
        $inventory->料號 = '4150-00A8000';
        $inventory->現有庫存 = 60;
        $inventory->儲位 = '7-A019';
        $inventory->客戶別 = 'Fendi';
        $inventory->盤點 = null;
        $inventory->save();
        //
        $inventory = new Checking_inventory;
        $inventory->單號 = "2_2021-04-20_10:21:15";
        $inventory->料號 = '4017-01HL000';
        $inventory->現有庫存 = 100;
        $inventory->儲位 = '1-2';
        $inventory->客戶別 = 'ARRIS';
        $inventory->盤點 = null;
        $inventory->save();
        //
        $inventory = new Checking_inventory;
        $inventory->單號 = "2_2021-04-20_10:21:15";
        $inventory->料號 = '4152-01EV000';
        $inventory->現有庫存 = 18;
        $inventory->儲位 = '7-A05';
        $inventory->客戶別 = 'Omega';
        $inventory->盤點 = null;
        $inventory->save();
        //-----------------------------------------------
        $inventory = new Checking_inventory;
        $inventory->單號 = "1_2021-06-09_10:42:01";
        $inventory->料號 = '4150-00A8000';
        $inventory->現有庫存 = 60;
        $inventory->儲位 = '7-A019';
        $inventory->客戶別 = 'Fendi';
        $inventory->盤點 = null;
        $inventory->save();
        //
        $inventory = new Checking_inventory;
        $inventory->單號 = "1_2021-06-09_10:42:01";
        $inventory->料號 = '4017-01HL000';
        $inventory->現有庫存 = 100;
        $inventory->儲位 = '1-2';
        $inventory->客戶別 = 'ARRIS';
        $inventory->盤點 = null;
        $inventory->save();
        //
        $inventory = new Checking_inventory;
        $inventory->單號 = "1_2021-06-09_10:42:01";
        $inventory->料號 = '4152-01EV000';
        $inventory->現有庫存 = 18;
        $inventory->儲位 = '7-A05';
        $inventory->客戶別 = 'Omega';
        $inventory->盤點 = null;
        $inventory->save();
    }
}
