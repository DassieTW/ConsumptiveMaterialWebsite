<?php

namespace Database\Seeders;
use App\Models\DB_List;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DB_ListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "DB_List";

    public function run()
    {
        //
        $dblist = new DB_List;
        $dblist->廠區 = 'BB1';
        $dblist->IP = '172.22.252.11';
        $dblist->DB_Name = 'BB1 Consumables management';
        $dblist->save();
        //
        $dblist = new DB_List;
        $dblist->廠區 = 'BB4';
        $dblist->IP = '172.22.252.11';
        $dblist->DB_Name = 'BB4 Consumables management';
        $dblist->save();
        //
        $dblist = new DB_List;
        $dblist->廠區 = 'M1';
        $dblist->IP = '172.22.252.11';
        $dblist->DB_Name = 'M1 Consumables management';
        $dblist->save();
        //
        $dblist = new DB_list;
        $dblist->廠區 = 'M2';
        $dblist->IP = '172.22.252.11';
        $dblist->DB_Name = 'M2 Consumables management';
        $dblist->save();
    }
}
