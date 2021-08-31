<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new Models\User;
        $user->name = '1';
        $user->工號 = 'LA12340000';
        $user->password = \Hash::make('111');
        $user->email = '1@gmail.com';
        $user->save();

        $user = new Models\User;
        $user->name = 'test';
        $user->工號 = 'LA12340001';
        $user->password = \Hash::make('222');
        $user->email = '2@yahoo.com.tw';
        $user->save();
    }
}
