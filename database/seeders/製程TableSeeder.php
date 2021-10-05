<?php

namespace Database\Seeders;
use App\Models\製程;
use Illuminate\Database\Seeder;

class 製程TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "製程";

    public function run()
    {
        //
        $production = new 製程;
        $production->製程 = 'FAE';
        $production->save();
        //
        $production = new 製程;
        $production->製程 = 'LCON';
        $production->save();
        //
        $production = new 製程;
        $production->製程 = 'PD';
        $production->save();
        //
        $production = new 製程;
        $production->製程 = '包裝';
        $production->save();
    }
}
