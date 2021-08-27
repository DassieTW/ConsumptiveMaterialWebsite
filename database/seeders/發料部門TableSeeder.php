<?php

namespace Database\Seeders;
use App\Models\發料部門;
use Illuminate\Database\Seeder;

class 發料部門TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "發料部門";

    public function run()
    {
        //
        $send = new 發料部門;
        $send->發料部門 = 'IE備品室';
        $send->save();
        //
        $send = new 發料部門;
        $send->發料部門 = 'ME備品室';
        $send->save();
        //
        $send = new 發料部門;
        $send->發料部門 = '設備備品室';
        $send->save();

    }
}
