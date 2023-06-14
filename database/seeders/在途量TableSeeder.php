<?php

namespace Database\Seeders;

use App\Models\在途量;
use Illuminate\Database\Seeder;

class 在途量TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "在途量";

    public function run()
    {
        \DB::table('在途量')->insert([
            '客戶' => 'ARRIS',
            '料號' => '4151-001V000',
            '請購數量' => 60
        ]);
        //
        \DB::table('在途量')->insert([
            '客戶' => 'GUCCI',
            '料號' => '4017-01HL000',
            '請購數量' => 150
        ]);
        //
        \DB::table('在途量')->insert([
            '客戶' => 'NAPA',
            '料號' => '4017-01HL000',
            '請購數量' => 800
        ]);
    }
}
