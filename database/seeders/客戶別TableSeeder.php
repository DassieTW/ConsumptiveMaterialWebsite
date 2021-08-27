<?php

namespace Database\Seeders;
use App\Models\客戶別;
use Illuminate\Database\Seeder;

class 客戶別TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "客戶別";

    public function run()
    {
        //
        $client = new 客戶別;
        $client->客戶 = 'AETHEROS';
        $client->save();
        //
        $client = new 客戶別;
        $client->客戶 = 'ALL';
        $client->save();
        //
        $client = new 客戶別;
        $client->客戶 = 'Cisco';
        $client->save();
        //
        $client = new 客戶別;
        $client->客戶 = 'NAPA';
        $client->save();
        //
        $client = new 客戶別;
        $client->客戶 = 'PACE';
        $client->save();
<<<<<<< HEAD
=======
        //
        $client = new 客戶別;
        $client->客戶 = 'Fendi';
        $client->save();
>>>>>>> 0827tony
    }
}
