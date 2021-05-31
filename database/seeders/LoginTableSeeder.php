<?php

namespace Database\Seeders;
use App\Models\Login;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class LoginTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $table = "login";

    public function run()
    {
        //
        $login = new Login;
        $login->username = '1';
        $login->password = Hash::make('111');
        $login->priority = 1;
        $login->姓名 = '權限1_測試';
        $login->save();

        $login2 = new Login;
        $login2->username = '2';
        $login2->password = Hash::make('222');
        $login2->priority = 2;
        $login2->姓名 = '權限2_測試';
        $login2->save();

        $login3 = new Login;
        $login3->username = '3';
        $login3->password = Hash::make('333');
        $login3->priority = 3;
        $login3->姓名 = '權限3_測試';
        $login3->save();

        $login4 = new Login;
        $login4->username = '4';
        $login4->password = Hash::make('444');
        $login4->priority = 4;
        $login4->姓名 = '權限4_測試';
        $login4->save();

    }
}
