<?php

namespace Database\Seeders;
use App\Models\O庫Material;
use Illuminate\Database\Seeder;

class O庫MaterialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "O庫_material";

    public function run()
    {
        //
        $material = new O庫Material;
        $material->料號 = '0432-00KX000';
        $material->品名 = 'ADAPTER SWITCHING 15V/1.5A EU';
        $material->規格 = 'OEM/ADS0271-B 150150';
        $material->save();
    }
}
