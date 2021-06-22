<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(
            [
                SubjectTableSeeder::class,
                PostTableSeeder::class,
                UserTableSeeder::class,
                ConsumptiveMaterialTableSeeder::class,
                LoginTableSeeder::class,
                人員信息TableSeeder::class,
                廠別TableSeeder::class,
                客戶別TableSeeder::class,
                機種TableSeeder::class,
                製程TableSeeder::class,
                線別TableSeeder::class,
                領用部門TableSeeder::class,
                領用原因TableSeeder::class,
                入庫原因TableSeeder::class,
                儲位TableSeeder::class,
                發料部門TableSeeder::class,
                AuthorizationManagementTableSeeder::class,
                InboundTableSeeder::class,
                InventoryTableSeeder::class,
                MPSTableSeeder::class,
                OutboundTableSeeder::class,
                O庫MaterialTableSeeder::class,
                月請購_站位TableSeeder::class,
                月請購_單耗TableSeeder::class,
                出庫退料TableSeeder::class,
                在途量TableSeeder::class,
                非月請購TableSeeder::class,
            ]
        );
    }
}
