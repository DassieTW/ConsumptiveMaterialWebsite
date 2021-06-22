<?php

namespace Database\Seeders;
use App\Models\領用原因;
use Illuminate\Database\Seeder;

class 領用原因TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "領用原因";

    public function run()
    {
        //
        $usereason = new 領用原因;
        $usereason->領用原因 = '不良更換';
        $usereason->save();
        //
        $usereason = new 領用原因;
        $usereason->領用原因 = '生產領用';
        $usereason->save();
        //
        $usereason = new 領用原因;
        $usereason->領用原因 = '架線領用';
        $usereason->save();
        //
        $usereason = new 領用原因;
        $usereason->領用原因 = '新人使用';
        $usereason->save();
    }
}
