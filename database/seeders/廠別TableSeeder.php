<?php

namespace Database\Seeders;
use App\Models\廠別;
use Illuminate\Database\Seeder;

class 廠別TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "廠別";

    public function run()
    {
        //
        $factory = new 廠別;
        $factory->廠別 = 'BB1';
        $factory->save();
        //
        $factory = new 廠別;
        $factory->廠別 = 'BB4';
        $factory->save();
        //
        $factory = new 廠別;
        $factory->廠別 = 'M1';
        $factory->save();
        //
        $factory = new 廠別;
        $factory->廠別 = '五廠SMT';
        $factory->save();
    }
}
