<?php

namespace Database\Seeders;
use App\Models\線別;
use Illuminate\Database\Seeder;

class 線別TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "線別";

    public function run()
    {
        //
        $line = new 線別;
        $line->線別 = '2C';
        $line->save();
        //
        $line = new 線別;
        $line->線別 = '2L';
        $line->save();
        //
        $line = new 線別;
        $line->線別 = '3F04';
        $line->save();
        //
        $line = new 線別;
        $line->線別 = 'D06';
        $line->save();
    }
}
