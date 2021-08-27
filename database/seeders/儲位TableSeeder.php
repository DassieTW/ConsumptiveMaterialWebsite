<?php

namespace Database\Seeders;
use App\Models\儲位;
use Illuminate\Database\Seeder;

class 儲位TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "儲位";

    public function run()
    {
        //
        $position = new 儲位;
        $position->儲存位置 = '2-2-7';
        $position->save();
        //
        $position = new 儲位;
        $position->儲存位置 = '7-A003';
        $position->save();
        //
        $position = new 儲位;
        $position->儲存位置 = '7-A098';
        $position->save();
        //
        $position = new 儲位;
        $position->儲存位置 = 'Mcaca-01';
        $position->save();
<<<<<<< HEAD
=======
        //
        $position = new 儲位;
        $position->儲存位置 = '7-A019';
        $position->save();
        //
        $position = new 儲位;
        $position->儲存位置 = '1-2';
        $position->save();
>>>>>>> 0827tony
    }
}
