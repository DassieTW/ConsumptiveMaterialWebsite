<?php

namespace Database\Seeders;
use App\Models\入庫原因;
use Illuminate\Database\Seeder;

class 入庫原因TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "入庫原因";

    public function run()
    {
        //
        $inreason = new 入庫原因;
        $inreason->入庫原因 = '來料';
        $inreason->save();
        //
        $inreason = new 入庫原因;
        $inreason->入庫原因 = '退庫';
        $inreason->save();
        //
        $inreason = new 入庫原因;
        $inreason->入庫原因 = '新進入庫';
        $inreason->save();
        //
        $inreason = new 入庫原因;
        $inreason->入庫原因 = '調撥';
        $inreason->save();
        //
    }
}
