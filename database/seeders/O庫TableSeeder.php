<?php

namespace Database\Seeders;
use App\Models\O庫;
use Illuminate\Database\Seeder;

class O庫TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "O庫";

    public function run()
    {
        //
        $o = new O庫;
        $o->O庫 = '9741';
        $o->save();
        //
        $o = new O庫;
        $o->O庫 = '9743';
        $o->save();
        //
        $o = new O庫;
        $o->O庫 = 'O庫測試';
        $o->save();
        //
        $o = new O庫;
        $o->O庫 = 'O庫測試2';
        $o->save();
    }
}
