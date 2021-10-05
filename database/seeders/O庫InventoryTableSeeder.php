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
        //
        $oinventory = new O庫Inventory;
        $oinventory->料號 = '0432-00KX000';
        $oinventory->現有庫存 = 51;
        $oinventory->客戶別 = 'Cisco';
        $oinventory->庫別 = 'O庫測試';
        $oinventory->最後更新時間 = Carbon::create(2021, 6, 27, 16, 24, 47, 'GMT');
        $oinventory->品名 = 'ADAPTER SWITCHING 15V/1.5A EU';
        $oinventory->規格 = 'OEM/ADS0271-B 150150';
        $oinventory->save();
        //
        $oinventory = new O庫Inventory;
        $oinventory->料號 = '0432-00KX000';
        $oinventory->現有庫存 = 89;
        $oinventory->客戶別 = 'Fendi';
        $oinventory->庫別 = 'O庫測試1';
        $oinventory->最後更新時間 = Carbon::create(2021, 6, 27, 17, 13, 8, 'GMT');
        $oinventory->品名 = 'ADAPTER SWITCHING 15V/1.5A EU';
        $oinventory->規格 = 'OEM/ADS0271-B 150150';
        $oinventory->save();
        //
        $oinventory = new O庫Inventory;
        $oinventory->料號 = 'TEST3';
        $oinventory->現有庫存 = 264;
        $oinventory->客戶別 = 'Fendi';
        $oinventory->庫別 = '9743';
        $oinventory->最後更新時間 = Carbon::create(2021, 7, 2, 15, 29, 20, 'GMT');
        $oinventory->品名 = '品名3';
        $oinventory->規格 = '規格3';
        $oinventory->save();
    }
}
