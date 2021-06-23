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
        //
        $travel = new 在途量;
        $travel->客戶 = 'ARRIS';
        $travel->料號 = '4151-001V000';
        $travel->請購數量 = 60;
        $travel->save();
        //
        $travel = new 在途量;
        $travel->客戶 = 'GUCCI';
        $travel->料號 = '4017-01HL000';
        $travel->請購數量 = 150;
        $travel->save();
        //
        $travel = new 在途量;
        $travel->客戶 = 'NAPA';
        $travel->料號 = '4017-01HL000';
        $travel->請購數量 = 800;
        $travel->save();
    }
}
