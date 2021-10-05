<?php

namespace Database\Seeders;
use App\Models\機種;
use Illuminate\Database\Seeder;

class 機種TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "機種";

    public function run()
    {
        //
        $machine = new 機種;
        $machine->機種 = 'ALL';
        $machine->save();
        //
        $machine = new 機種;
        $machine->機種 = 'EZ1K';
        $machine->save();
        //
        $machine = new 機種;
        $machine->機種 = 'ONYX';
        $machine->save();
        //
        $machine = new 機種;
        $machine->機種 = 'R650';
        $machine->save();
    }
}
