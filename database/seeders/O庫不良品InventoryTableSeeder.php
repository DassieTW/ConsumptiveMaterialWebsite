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
        //
        $oinventory = new O庫不良品Inventory;
        $oinventory->料號 = '0432-00KX000';
        $oinventory->現有庫存 = 87;
        $oinventory->客戶別 = 'Cisco';
        $oinventory->庫別 = 'O庫測試1';
        $oinventory->最後更新時間 = Carbon::create(2021, 6, 27, 17, 13, 8, 'GMT');
        $oinventory->品名 = 'ADAPTER SWITCHING 15V/1.5A EU';
        $oinventory->規格 = 'OEM/ADS0271-B 150150';
        $oinventory->save();
        //
        $oinventory = new O庫不良品Inventory;
        $oinventory->料號 = '0432-00KX000';
        $oinventory->現有庫存 = 88;
        $oinventory->客戶別 = 'Fendi';
        $oinventory->庫別 = 'O庫測試2';
        $oinventory->最後更新時間 = Carbon::create(2021, 6, 27, 17, 13, 8, 'GMT');
        $oinventory->品名 = 'ADAPTER SWITCHING 15V/1.5A EU';
        $oinventory->規格 = 'OEM/ADS0271-B 150150';
        $oinventory->save();
        //
    }
}
