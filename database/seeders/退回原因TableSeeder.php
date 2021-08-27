<?php

namespace Database\Seeders;
use App\Models\退回原因;
use Illuminate\Database\Seeder;

class 退回原因TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "退回原因";

    public function run()
    {
        //
        $backreason = new 退回原因;
        $backreason->退回原因 = '來料不良';
        $backreason->save();
        //
    }
}
