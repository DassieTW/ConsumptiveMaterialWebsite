<?php

namespace Database\Seeders;
use App\Models\人員信息;
use Illuminate\Database\Seeder;

class 人員信息TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "人員信息";

    public function run()
    {
        //
        $information = new 人員信息;
        $information->工號 = 'LA1401658';
        $information->姓名 = 'O';
        $information->部門 = 'O';
        $information->save();
        //
        $information = new 人員信息;
        $information->工號 = 'S09193426';
        $information->姓名 = '凌紅霞';
        $information->部門 = 'B606C20M0E';
        $information->save();
        //
        $information = new 人員信息;
        $information->工號 = 'S18018374';
        $information->姓名 = '常雙良';
        $information->部門 = '備品室';
        $information->save();
        //
        $information = new 人員信息;
        $information->工號 = 'S21015013';
        $information->姓名 = '李轉弟';
        $information->部門 = 'FATP';
        $information->save();
        //
    }
}
