<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models;

class ConsumptiveMaterialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $consumptiveMats = new Models\ConsumptiveMaterial;
        $consumptiveMats->料號 = '4017-01HL000';
        $consumptiveMats->品名 = '腳杯' ;
        $consumptiveMats->規格 = '綬康凳子配件';
        $consumptiveMats->單價 = 2.9 ;
        $consumptiveMats->幣別 = 'RMB';
        $consumptiveMats->單位 = 'pcs' ;
        $consumptiveMats->MPQ = 1 ;
        $consumptiveMats->MOQ = 50 ;
        $consumptiveMats->LT = 21 ;
        $consumptiveMats->月請購 = '否' ;
        $consumptiveMats->A級資材 = '否' ;
        // $consumptiveMats->耗材歸屬 = '站位' ;
        $consumptiveMats->發料部門 = 'IE備品室';
        $consumptiveMats->安全庫存 = 20 ;
        $consumptiveMats->save();

        // ------------------------------------------------

        $consumptiveMats = new Models\ConsumptiveMaterial;
        $consumptiveMats->料號 = '4107-007R000';
        $consumptiveMats->品名 = '烙鐵頭' ;
        $consumptiveMats->規格 = 'SMB15141W邁威';
        $consumptiveMats->單價 = 78 ;
        $consumptiveMats->幣別 = 'RMB';
        $consumptiveMats->單位 = 'pcs' ;
        $consumptiveMats->MPQ = 1 ;
        $consumptiveMats->MOQ = 1 ;
        $consumptiveMats->LT = 15 ;
        $consumptiveMats->月請購 = '是' ;
        $consumptiveMats->A級資材 = '否' ;
        // $consumptiveMats->耗材歸屬 = '單耗' ;
        $consumptiveMats->發料部門 = 'IE備品室';
        $consumptiveMats->安全庫存 = null ;
        $consumptiveMats->save();

        // ------------------------------------------------

        $consumptiveMats = new Models\ConsumptiveMaterial;
        $consumptiveMats->料號 = '4150-00A8000';
        $consumptiveMats->品名 = '可換頭不鏽鋼防靜電鑷子' ;
        $consumptiveMats->規格 = '國產,ESD-249';
        $consumptiveMats->單價 = 4 ;
        $consumptiveMats->幣別 = 'RMB';
        $consumptiveMats->單位 = 'pcs' ;
        $consumptiveMats->MPQ = 1 ;
        $consumptiveMats->MOQ = 1 ;
        $consumptiveMats->LT = 15 ;
        $consumptiveMats->月請購 = '是' ;
        $consumptiveMats->A級資材 = '否' ;
        // $consumptiveMats->耗材歸屬 = '站位' ;
        $consumptiveMats->發料部門 = 'IE備品室';
        $consumptiveMats->安全庫存 = null ;
        $consumptiveMats->save();

        // ------------------------------------------------

        $consumptiveMats = new Models\ConsumptiveMaterial;
        $consumptiveMats->料號 = '4152-005B000';
        $consumptiveMats->品名 = '電動起子頭' ;
        $consumptiveMats->規格 = 'D4*60*D2*25*T5 GOOD';
        $consumptiveMats->單價 = 11 ;
        $consumptiveMats->幣別 = 'RMB';
        $consumptiveMats->單位 = 'pcs' ;
        $consumptiveMats->MPQ = 1 ;
        $consumptiveMats->MOQ = 1 ;
        $consumptiveMats->LT = 6 ;
        $consumptiveMats->月請購 = '是' ;
        $consumptiveMats->A級資材 = '否' ;
        // $consumptiveMats->耗材歸屬 = '單耗' ;
        $consumptiveMats->發料部門 = 'IE備品室';
        $consumptiveMats->安全庫存 = 1 ;
        $consumptiveMats->save();

        // ------------------------------------------------

        $consumptiveMats = new Models\ConsumptiveMaterial;
        $consumptiveMats->料號 = '4152-01ER000';
        $consumptiveMats->品名 = '電動起子頭' ;
        $consumptiveMats->規格 = 'D4*40*D2*20*2IP 尾缺,固德';
        $consumptiveMats->單價 = 43 ;
        $consumptiveMats->幣別 = 'RMB';
        $consumptiveMats->單位 = 'PCS' ;
        $consumptiveMats->MPQ = 1 ;
        $consumptiveMats->MOQ = 10 ;
        $consumptiveMats->LT = 15 ;
        $consumptiveMats->月請購 = '是' ;
        $consumptiveMats->A級資材 = '否' ;
        // $consumptiveMats->耗材歸屬 = '單耗' ;
        $consumptiveMats->發料部門 = 'IE備品室';
        $consumptiveMats->安全庫存 = null ;
        $consumptiveMats->save();
    } // run
} // end class
