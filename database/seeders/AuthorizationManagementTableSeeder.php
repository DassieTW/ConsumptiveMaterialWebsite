<?php

namespace Database\Seeders;
use App\Models\AuthorizationManagement;
use Illuminate\Database\Seeder;

class AuthorizationManagementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $table = "authorization_management";

    public function run()
    {
        //
        $authorization = new AuthorizationManagement;
        $authorization->authorization = 'O庫_Page';
        $authorization->priority = 1234;
        $authorization->save();
        //
        $authorization = new AuthorizationManagement;
        $authorization->authorization = '人員信息_查詢';
        $authorization->priority = 123;
        $authorization->save();
        //
        $authorization = new AuthorizationManagement;
        $authorization->authorization = '入庫_Page';
        $authorization->priority = 123;
        $authorization->save();
        //
        $authorization = new AuthorizationManagement;
        $authorization->authorization = '新增用戶';
        $authorization->priority = 1;
        $authorization->save();

    }
}
